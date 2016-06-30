<?php

namespace Ecodev\Newsletter;

use DateTime;
use Ecodev\Newsletter\Domain\Model\Newsletter;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * Toolbox for newsletter and dependant extensions.
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
abstract class Tools
{
    protected static $configuration = null;

    /**
     * Get a newsletter-conf-template parameter
     *
     * @param string $key Parameter key
     * @return mixed Parameter value
     */
    public static function confParam($key)
    {
        // Look for a config in the module TS first.
        static $configTS;
        if (!is_array($configTS) && isset($GLOBALS['TYPO3_DB'])) {
            $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
            $beConfManager = $objectManager->get(\TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager::class);
            $configTS = $beConfManager->getTypoScriptSetup();
            $configTS = $configTS['module.']['tx_newsletter.']['config.'];
        }

        if (isset($configTS[$key])) {
            return $configTS[$key];
        }

        // Else fallback to the extension config.
        if (!is_array(self::$configuration)) {
            self::$configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['newsletter']);
        }

        return self::$configuration[$key];
    }

    /**
     * Returns a logger for given class
     *
     * @param string $class
     * @return \TYPO3\CMS\Core\Log\Logger
     */
    public static function getLogger($class)
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger($class);
    }

    /**
     * Create a configured mailer from a newsletter page record.
     * This mailer will have both plain and html content applied as well as files attached.
     *
     * @param \Ecodev\Newsletter\Domain\Model\Newsletter The newsletter
     * @param int $language
     * @return \Ecodev\Newsletter\Mailer preconfigured mailer for sending
     */
    public static function getConfiguredMailer(Newsletter $newsletter, $language = null)
    {
        // Configure the mailer
        $mailer = new Mailer();
        $mailer->setNewsletter($newsletter, $language);

        // hook for modifing the mailer before finish preconfiguring
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newsletter']['getConfiguredMailerHook'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newsletter']['getConfiguredMailerHook'] as $_classRef) {
                $_procObj = \TYPO3\CMS\Core\Utility\GeneralUtility::getUserObj($_classRef);
                $mailer = $_procObj->getConfiguredMailerHook($mailer, $newsletter);
            }
        }

        return $mailer;
    }

    /**
     * Create the spool for all newsletters who need it
     */
    public static function createAllSpool()
    {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        $newsletterRepository = $objectManager->get(\Ecodev\Newsletter\Domain\Repository\NewsletterRepository::class);

        $newsletters = $newsletterRepository->findAllReadyToSend();
        foreach ($newsletters as $newsletter) {
            self::createSpool($newsletter);
        }
    }

    /**
     * Spool a newsletter page out to the real receivers.
     *
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @param Newsletter $newsletter
     */
    public static function createSpool(Newsletter $newsletter)
    {
        global $TYPO3_DB;

        // If newsletter is locked because spooling now, or already spooled, then skip
        if ($newsletter->getBeginTime()) {
            return;
        }

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        $newsletterRepository = $objectManager->get(\Ecodev\Newsletter\Domain\Repository\NewsletterRepository::class);

        // Lock the newsletter by setting its begin_time
        $begintime = new DateTime();
        $newsletter->setBeginTime($begintime);
        $newsletterRepository->updateNow($newsletter);

        $emailSpooledCount = 0;
        $recipientList = $newsletter->getRecipientList();
        $recipientList->init();
        while ($receiver = $recipientList->getRecipient()) {

            // Register the recipient
            if (\TYPO3\CMS\Core\Utility\GeneralUtility::validEmail($receiver['email'])) {
                $TYPO3_DB->exec_INSERTquery('tx_newsletter_domain_model_email', [
                    'pid' => $newsletter->getPid(),
                    'recipient_address' => $receiver['email'],
                    'recipient_data' => serialize($receiver),
                    'newsletter' => $newsletter->getUid(),
                    'auth_code' => 'MD5(CONCAT(uid, recipient_address))',
                        ], ['auth_code']);
                ++$emailSpooledCount;
            }
        }
        self::getLogger(__CLASS__)->info("Queued $emailSpooledCount emails to be sent for newsletter " . $newsletter->getUid());

        // Schedule repeated newsletter if any
        $newsletter->scheduleNextNewsletter();

        // Unlock the newsletter by setting its end_time
        $newsletter->setEndTime(new DateTime());
        $newsletterRepository->updateNow($newsletter);
    }

    /**
     * Run the spool for all Newsletters, with a security to avoid parallel sending
     *
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     */
    public static function runAllSpool()
    {
        global $TYPO3_DB;

        // Try to detect if a spool is already running
        // If there is no records for the last 15 seconds, previous spool session is assumed to have ended.
        // If there are newer records, then stop here, and assume the running mailer will take care of it.
        $rs = $TYPO3_DB->sql_query('SELECT COUNT(uid) FROM tx_newsletter_domain_model_email WHERE end_time > ' . (time() - 15));
        list($num_records) = $TYPO3_DB->sql_fetch_row($rs);
        if ($num_records != 0) {
            return;
        }

        self::runSpool();
    }

    /**
     * Run the spool for one or all Newsletters
     *
     * @param Newsletter $limitNewsletter if specified, run spool only for that Newsletter
     */
    public static function runSpool(Newsletter $limitNewsletter = null)
    {
        $emailSentCount = 0;
        $mailers = [];

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        $newsletterRepository = $objectManager->get(\Ecodev\Newsletter\Domain\Repository\NewsletterRepository::class);
        $emailRepository = $objectManager->get(\Ecodev\Newsletter\Domain\Repository\EmailRepository::class);

        $allUids = $newsletterRepository->findAllNewsletterAndEmailUidToSend($limitNewsletter);

        $oldNewsletterUid = null;
        foreach ($allUids as $uids) {
            $newsletterUid = $uids['newsletter'];
            $emailUid = $uids['email'];

            /* For the page, this way we can support multiple pages in one spool session */
            if ($newsletterUid != $oldNewsletterUid) {
                $oldNewsletterUid = $newsletterUid;
                $mailers = [];

                $newsletter = $newsletterRepository->findByUid($newsletterUid);
            }

            // Define the language of email
            $email = $emailRepository->findByUid($emailUid);
            $recipientData = $email->getRecipientData();
            $language = $recipientData['L'];

            // Was a language with this page defined, if not create one
            if (!is_object($mailers[$language])) {
                $mailers[$language] = self::getConfiguredMailer($newsletter, $language);
            }

            // Mark it as started sending
            $email->setBeginTime(new DateTime());
            $emailRepository->updateNow($email);

            // Send the email
            $mailers[$language]->send($email);

            // Mark it as sent already
            $email->setEndTime(new DateTime());
            $emailRepository->updateNow($email);

            ++$emailSentCount;
        }

        // Log numbers
        self::getLogger(__CLASS__)->info("Sent $emailSentCount emails");
    }

    /**
     * Returns an base64_encode encrypted string
     * @param string $string
     * @return string base64_encode encrypted string
     */
    public static function encrypt($string)
    {
        $iv = mcrypt_create_iv(self::getIVSize(), MCRYPT_DEV_URANDOM);

        return base64_encode($iv . mcrypt_encrypt(MCRYPT_RIJNDAEL_256, self::getSecureKey(), $string, MCRYPT_MODE_CBC, $iv));
    }

    /**
     * Returns a decrypted string
     * @param string $string base64_encode encrypted string
     * @return string decrypted string
     */
    public static function decrypt($string)
    {
        $string = base64_decode($string);
        $iv = substr($string, 0, self::getIVSize());
        $cipher = substr($string, self::getIVSize());

        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, self::getSecureKey(), $cipher, MCRYPT_MODE_CBC, $iv));
    }

    /**
     * Returns the size of the IV
     * @return int
     */
    private static function getIVSize()
    {
        static $iv_size;
        if (!isset($iv_size)) {
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
        }

        return $iv_size;
    }

    /**
     * Returns the secure encryption key
     * @return string
     */
    private static function getSecureKey()
    {
        static $secureKey;
        if (!isset($secureKey)) {
            $secureKey = hash('sha256', $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'], true);
        }

        return $secureKey;
    }

    /**
     * Return the full user agent string to be used in HTTP headers
     * @return string
     */
    public static function getUserAgent()
    {
        // Fetch version manually to keep compatibility with TYPO3 6.2 to TYPO3 7.4
        $_EXTKEY = 'newsletter';
        $EM_CONF = [];
        require \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY, '/ext_emconf.php');
        $version = $EM_CONF[$_EXTKEY]['version'];

        $userAgent = TYPO3_user_agent . ' Newsletter/' . $version . ' (https://github.com/Ecodev/newsletter)';

        return $userAgent;
    }

    /**
     * Fetch and returns the content at specified URL
     * @param string $url
     * @return string
     */
    public static function getUrl($url)
    {
        // Specify User-Agent header if we fetch an URL, but not if it's a file on disk
        if (Utility\Uri::isAbsolute($url)) {
            $headers = [self::getUserAgent()];
        } else {
            $headers = [self::getUserAgent()];
        }

        $report = [];
        $content = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($url, 0, $headers, $report);

        // Throw Exception if content could not be fetched so that it is properly caught in Validador
        if ($content === false) {
            throw new \Exception('Could not fetch "' . $url . '"' . PHP_EOL . 'Error: ' . $report['error'] . PHP_EOL . 'Message: ' . $report['message']);
        }

        return $content;
    }

    /**
     * Returns the iconfile prefix
     * @return string
     */
    public static function getIconfilePrefix()
    {
        // From TYPO3 7.4.0 onward we must use EXT prefix
        if (version_compare(TYPO3_version, '7.4.0', '>=')) {
            return 'EXT:newsletter/';
        } else {
            // But for TYPO3 6.2 family, we still have to use old style
            return \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('newsletter');
        }
    }
}

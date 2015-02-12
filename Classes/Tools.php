<?php


namespace Ecodev\Newsletter;

use ExtensionManagementUtility;
use Ecodev\Newsletter\Domain\Model\Newsletter;
use Ecodev\Newsletter\Mailer;
use GeneralUtility;
use Ecodev\Newsletter\Tools as EcodevNewsletterTools;
use DateTime;
use tslib_fe;
use TYPO3;
use \TYPO3\CMS\Extbase\Core\Bootstrap;



/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014
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

require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('newsletter') . '/Classes/Mailer.php');

/**
 * Toolbox for newsletter and dependant extensions.
 *
 * @abstract
 * @static
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
abstract class Tools
{

    protected static $configuration = null;

    /**
     * UriBuilder
     * @var \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
     */
    protected static $uriBuilder = null;

    /**
     * Get a newsletter-conf-template parameter
     *
     * @param    string   Parameter key
     * @return   mixed    Parameter value
     */
    public static function confParam($key)
    {
        if (!is_array(self::$configuration)) {
            self::$configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['newsletter']);
        }

        return self::$configuration[$key];
    }

    /**
     * Log a message in database table sys_log
     *
     * @global \TYPO3\CMS\Core\Authentication\BackendUserAuthentication $BE_USER
     * @param string $message
     * @param integer $logLevel 0 = message, 1 = error
     */
    public static function log($message, $logLevel = 0)
    {
        global $BE_USER;
        if ($BE_USER instanceof \TYPO3\CMS\Core\Authentication\BackendUserAuthentication) {
            $BE_USER->simplelog($message, 'newsletter', $logLevel);
        }
    }

    /**
     * Create a configured mailer from a newsletter page record.
     * This mailer will have both plain and html content applied as well as files attached.
     *
     * @param \Ecodev\Newsletter\Domain\Model\Newsletter The newsletter
     * @param integer $language
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
                $_procObj = & \TYPO3\CMS\Core\Utility\GeneralUtility::getUserObj($_classRef);
                $mailer = $_procObj->getConfiguredMailerHook($mailer, $newsletter);
            }
        }

        return $mailer;
    }

    /**
     * Create the spool for all newsletters who need it
     * @param boolean $onlyTest if true only test newsletter will be used, otherwise all (included tests)
     */
    static public function createAllSpool()
    {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\Extbase\Object\ObjectManager');
        $newsletterRepository = $objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\NewsletterRepository');

        $newsletters = $newsletterRepository->findAllReadyToSend();
        foreach ($newsletters as $newsletter) {
            EcodevNewsletterTools::createSpool($newsletter);
        }
    }

    /**
     * Spool a newsletter page out to the real receivers.
     *
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @param   array        Newsletter record.
     * @param   integer      Actual begin time.
     * @return  void
     */
    static public function createSpool(Newsletter $newsletter)
    {
        global $TYPO3_DB;

        // If newsletter is locked because spooling now, or already spooled, then skip
        if ($newsletter->getBeginTime())
            return;

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\Extbase\Object\ObjectManager');
        $newsletterRepository = $objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\NewsletterRepository');

        // Lock the newsletter by setting its begin_time
        $begintime = new DateTime();
        $newsletter->setBeginTime($begintime);
        $newsletterRepository->updateNow($newsletter);

        $emailSpooledCount = 0;
        $recipientList = $newsletter->getRecipientList();
        $recipientList->init();
        while ($receiver = $recipientList->getRecipient()) {

            // Register the receiver
            if (\TYPO3\CMS\Core\Utility\GeneralUtility::validEmail($receiver['email'])) {
                $TYPO3_DB->exec_INSERTquery('tx_newsletter_domain_model_email', array(
                    'pid' => $newsletter->getPid(),
                    'recipient_address' => $receiver['email'],
                    'recipient_data' => serialize($receiver),
                    'pid' => $newsletter->getPid(),
                    'newsletter' => $newsletter->getUid(),
                ));
                $emailSpooledCount++;
            }
        }
        EcodevNewsletterTools::log("Queued $emailSpooledCount emails to be sent for newsletter " . $newsletter->getUid());

        // Schedule repeated newsletter if any
        $newsletter->scheduleNextNewsletter();

        // Unlock the newsletter by setting its end_time
        $newsletter->setEndTime(new DateTime());
        $newsletterRepository->updateNow($newsletter);
    }

    /**
     * Run the spool on a server.
     *
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @return  integer	Number of emails sent.
     */
    public static function runSpoolOneAll()
    {
        global $TYPO3_DB;

        /* Try to detect if a spool is already running
          If there is no records for the last 15 seconds, previous spool session is assumed to have ended.
          If there are newer records, then stop here, and assume the running mailer will take care of it.
         */
        $rs = $TYPO3_DB->sql_query('SELECT COUNT(uid) FROM tx_newsletter_domain_model_email WHERE end_time > ' . (time() - 15));

        list($num_records) = $TYPO3_DB->sql_fetch_row($rs);
        if ($num_records <> 0) {
            return;
        }

        /* Do we any limit to this session? */
        if ($mails_per_round = EcodevNewsletterTools::confParam('mails_per_round')) {
            $limit = " LIMIT 0, $mails_per_round ";
        }

        // Find the uid of emails and newsletters that need to be sent
        $rs = $TYPO3_DB->sql_query("SELECT tx_newsletter_domain_model_newsletter.uid, tx_newsletter_domain_model_email.uid
						FROM tx_newsletter_domain_model_email
						INNER JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid)
						WHERE tx_newsletter_domain_model_email.begin_time = 0
						ORDER BY tx_newsletter_domain_model_email.newsletter " . $limit);

        /* Do it, if there is any records */
        if ($numRows = $TYPO3_DB->sql_num_rows($rs)) {
            self::runSpool($rs);
        }

        return $numRows;
    }

    /**
     * Run the spool from a browser
     * This has some limitations. No load balance. Different permissions. And should have a mails_per_round-value
     *
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @return    void
     */
    static public function runSpoolOne(Newsletter $newsletter)
    {
        global $TYPO3_DB;


        /* Do we any limit to this session? */
        if ($mails_per_round = EcodevNewsletterTools::confParam('mails_per_round')) {
            $limit = " LIMIT 0, $mails_per_round ";
        }

        // Find the uid of emails and newsletters that need to be sent
        $rs = $TYPO3_DB->sql_query("SELECT tx_newsletter_domain_model_newsletter.uid, tx_newsletter_domain_model_email.uid
						FROM tx_newsletter_domain_model_email
						INNER JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid)
						WHERE tx_newsletter_domain_model_newsletter.uid = " . $newsletter->getUid() . "
						AND tx_newsletter_domain_model_email.begin_time = 0
						ORDER BY tx_newsletter_domain_model_email.newsletter " . $limit);

        /* Do it, if there is any records */
        if ($numRows = $TYPO3_DB->sql_num_rows($rs)) {
            self::runSpool($rs);
        }

        return $numRows;
    }

    /**
     * Method that accually runs the spool
     *
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @param resource SQL-resultset from a select from tx_newsletter_domain_model_email
     * @return void
     */
    private static function runSpool($rs)
    {
        global $TYPO3_DB;

        $emailSentCount = 0;
        $mailers = array();

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\Extbase\Object\ObjectManager');
        $newsletterRepository = $objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\NewsletterRepository');
        $emailRepository = $objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\EmailRepository');

        $oldNewsletterUid = null;
        while (list($newsletterUid, $emailUid) = $TYPO3_DB->sql_fetch_row($rs)) {

            /* For the page, this way we can support multiple pages in one spool session */
            if ($newsletterUid != $oldNewsletterUid) {
                $oldNewsletterUid = $newsletterUid;
                $mailers = array();

                $newsletter = $newsletterRepository->findByUid($newsletterUid);
            }

            // Define the language of email
            $email = $emailRepository->findByUid($emailUid);
            $recipientData = $email->getRecipientData();
            $L = $recipientData['L'];

            // Was a language with this page defined, if not create one
            if (!is_object($mailers[$L])) {
                $mailers[$L] = &EcodevNewsletterTools::getConfiguredMailer($newsletter, $L);
            }

            // Mark it as started sending
            $email->setBeginTime(new DateTime());
            $emailRepository->updateNow($email);

            // Send the email
            $mailers[$L]->send($email);

            // Mark it as sent already
            $email->setEndTime(new DateTime());
            $emailRepository->updateNow($email);

            $emailSentCount++;
        }

        // Log numbers to syslog
        EcodevNewsletterTools::log("Sent $emailSentCount emails");
    }

    /**
     * Build an uriBuilder that can be used from any context (backend, frontend, TCA) to generate frontend URI
     * @param string $extensionName
     * @param string $pluginName
     * @return \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
     */
    protected static function buildUriBuilder($extensionName, $pluginName)
    {

        // If we are in Backend we need to simulate minimal TSFE
        if (!isset($GLOBALS['TSFE']) || !($GLOBALS['TSFE'] instanceof tslib_fe)) {
            if (!is_object($GLOBALS['TT'])) {
                $GLOBALS['TT'] = new TYPO3\CMS\Core\TimeTracker\TimeTracker;
                $GLOBALS['TT']->start();
            }
            $TSFEclassName = @\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tslib_fe');
            $GLOBALS['TSFE'] = new $TSFEclassName($GLOBALS['TYPO3_CONF_VARS'], 0, '0', 1, '', '', '', '');
//			$GLOBALS['TSFE']->connectToMySQL();
            $GLOBALS['TSFE']->initFEuser();
            $GLOBALS['TSFE']->fetch_the_id();
            $GLOBALS['TSFE']->getPageAndRootline();
            $GLOBALS['TSFE']->initTemplate();
            $GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
            $GLOBALS['TSFE']->forceTemplateParsing = 1;
            $GLOBALS['TSFE']->getConfigArray();
        }

        // If extbase is not boostrapped yet, we must do it before building uriBuilder (when used from TCA)
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\Extbase\Object\ObjectManager');
        if (!(isset($GLOBALS['dispatcher']) && $GLOBALS['dispatcher'] instanceof \TYPO3\CMS\Extbase\Core\Bootstrap)) {
            $extbaseBootstrap = $objectManager->get('\TYPO3\CMS\Extbase\Core\Bootstrap');
            $extbaseBootstrap->initialize(array('extensionName' => $extensionName, 'pluginName' => $pluginName));
        }

        return $objectManager->get('\TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder');
    }

    /**
     * Returns a frontend URI independently of current context, with or without extbase, and with or without TSFE
     * @param string $actionName
     * @param array $controllerArguments
     * @param string $controllerName
     * @param string $extensionName
     * @param string $pluginName
     * @return string absolute URI
     */
    public static function buildFrontendUri($actionName, array $controllerArguments, $controllerName, $extensionName = 'newsletter', $pluginName = 'p')
    {
        if (!self::$uriBuilder)
            self::$uriBuilder = self::buildUriBuilder($extensionName, $pluginName);
        $controllerArguments['action'] = $actionName;
        $controllerArguments['controller'] = $controllerName;

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\Extbase\Object\ObjectManager');
        $extensionService = $objectManager->get('\TYPO3\CMS\Extbase\Service\ExtensionService');
        $pluginNamespace = $extensionService->getPluginNamespace($extensionName, $pluginName);

        $arguments = array($pluginNamespace => $controllerArguments);


        self::$uriBuilder
                ->reset()
                ->setUseCacheHash(FALSE)
                ->setCreateAbsoluteUri(TRUE)
                ->setArguments($arguments);

        return self::$uriBuilder->buildFrontendUri() . '&type=1342671779';
    }

}

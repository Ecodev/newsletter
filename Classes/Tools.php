<?php

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

require_once(PATH_t3lib . 'class.t3lib_extmgm.php');
require_once(PATH_t3lib . 'class.t3lib_befunc.php');
require_once(t3lib_extMgm::extPath('newsletter') . '/Classes/Mailer.php');

/**
 * Toolbox for newsletter and dependant extensions.
 *
 * @abstract
 * @static
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
abstract class Tx_Newsletter_Tools
{

    protected static $configuration = null;

    /**
     * UriBuilder
     * @var Tx_Extbase_MVC_Web_Routing_UriBuilder
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
     * @global t3lib_userAuthGroup $BE_USER
     * @param string $message
     * @param integer $logLevel 0 = message, 1 = error
     */
    public static function log($message, $logLevel = 0)
    {
        global $BE_USER;
        if ($BE_USER instanceof t3lib_userAuthGroup) {
            $BE_USER->simplelog($message, 'newsletter', $logLevel);
        }
    }

    /**
     * Create a configured mailer from a newsletter page record.
     * This mailer will have both plain and html content applied as well as files attached.
     *
     * @param Tx_Newsletter_Domain_Model_Newsletter The newsletter
     * @param integer $language
     * @return Tx_Newsletter_Mailer preconfigured mailer for sending
     */
    public static function getConfiguredMailer(Tx_Newsletter_Domain_Model_Newsletter $newsletter, $language = null)
    {
        // Configure the mailer
        $mailer = new Tx_Newsletter_Mailer();
        $mailer->setNewsletter($newsletter, $language);

        // hook for modifing the mailer before finish preconfiguring
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newsletter']['getConfiguredMailerHook'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newsletter']['getConfiguredMailerHook'] as $_classRef) {
                $_procObj = & t3lib_div::getUserObj($_classRef);
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
        $objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
        $newsletterRepository = $objectManager->get('Tx_Newsletter_Domain_Repository_NewsletterRepository');

        $newsletters = $newsletterRepository->findAllReadyToSend();
        foreach ($newsletters as $newsletter) {
            Tx_Newsletter_Tools::createSpool($newsletter);
        }
    }

    /**
     * Spool a newsletter page out to the real receivers.
     *
     * @global t3lib_DB $TYPO3_DB
     * @param   array        Newsletter record.
     * @param   integer      Actual begin time.
     * @return  void
     */
    static public function createSpool(Tx_Newsletter_Domain_Model_Newsletter $newsletter)
    {
        global $TYPO3_DB;

        // If newsletter is locked because spooling now, or already spooled, then skip
        if ($newsletter->getBeginTime())
            return;

        $objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
        $newsletterRepository = $objectManager->get('Tx_Newsletter_Domain_Repository_NewsletterRepository');

        // Lock the newsletter by setting its begin_time
        $begintime = new DateTime();
        $newsletter->setBeginTime($begintime);
        $newsletterRepository->updateNow($newsletter);

        $emailSpooledCount = 0;
        $recipientList = $newsletter->getRecipientList();
        $recipientList->init();
        while ($receiver = $recipientList->getRecipient()) {

            // Remove spaces before and after the address, because t3lib_div::validEmail return FALSE
            // if an email contains a trailing space
            $cleanEmail = trim($receiver['email']);

            // Register the receiver
            if (t3lib_div::validEmail($cleanEmail)) {
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
        Tx_Newsletter_Tools::log("Queued $emailSpooledCount emails to be sent for newsletter " . $newsletter->getUid());

        // Schedule repeated newsletter if any
        $newsletter->scheduleNextNewsletter();

        // Unlock the newsletter by setting its end_time
        $newsletter->setEndTime(new DateTime());
        $newsletterRepository->updateNow($newsletter);
    }

    /**
     * Run the spool on a server.
     *
     * @global t3lib_DB $TYPO3_DB
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
        if ($mails_per_round = Tx_Newsletter_Tools::confParam('mails_per_round')) {
            $limit = " LIMIT 0, $mails_per_round ";
        }

        /* Find the receivers, select userdata, uid of target, uid of page, uid of logrecord */
        $rs = $TYPO3_DB->sql_query("SELECT tx_newsletter_domain_model_newsletter.uid, tx_newsletter_domain_model_email.uid
						FROM tx_newsletter_domain_model_email
						LEFT JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid)
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
     * @global t3lib_DB $TYPO3_DB
     * @return    void
     */
    static public function runSpoolOne(Tx_Newsletter_Domain_Model_Newsletter $newsletter)
    {
        global $TYPO3_DB;


        /* Do we any limit to this session? */
        if ($mails_per_round = Tx_Newsletter_Tools::confParam('mails_per_round')) {
            $limit = " LIMIT 0, $mails_per_round ";
        }

        /* Find the receivers, select userdata, uid of target, uid of page, uid of logrecord */
        $rs = $TYPO3_DB->sql_query("SELECT tx_newsletter_domain_model_newsletter.uid, tx_newsletter_domain_model_email.uid
						FROM tx_newsletter_domain_model_email
						LEFT JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid)
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
     * @global t3lib_DB $TYPO3_DB
     * @param resource SQL-resultset from a select from tx_newsletter_domain_model_email
     * @return void
     */
    private static function runSpool($rs)
    {
        global $TYPO3_DB;

        $emailSentCount = 0;
        $mailers = array();

        $objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
        $newsletterRepository = $objectManager->get('Tx_Newsletter_Domain_Repository_NewsletterRepository');
        $emailRepository = $objectManager->get('Tx_Newsletter_Domain_Repository_EmailRepository');

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
                $mailers[$L] = &Tx_Newsletter_Tools::getConfiguredMailer($newsletter, $L);
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
        Tx_Newsletter_Tools::log("Sent $emailSentCount emails");
    }

    /**
     * Build an uriBuilder that can be used from any context (backend, frontend, TCA) to generate frontend URI
     * @param string $extensionName
     * @param string $pluginName
     * @return Tx_Extbase_MVC_Web_Routing_UriBuilder
     */
    protected static function buildUriBuilder($extensionName, $pluginName)
    {

        // If we are in Backend we need to simulate minimal TSFE
        if (!isset($GLOBALS['TSFE']) || !($GLOBALS['TSFE'] instanceof tslib_fe)) {
            if (!is_object($GLOBALS['TT'])) {
                $GLOBALS['TT'] = new t3lib_timeTrack;
                $GLOBALS['TT']->start();
            }
            $TSFEclassName = @t3lib_div::makeInstance('tslib_fe');
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
        $objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
        if (!(isset($GLOBALS['dispatcher']) && $GLOBALS['dispatcher'] instanceof Tx_Extbase_Core_Bootstrap)) {
            $extbaseBootstrap = $objectManager->get('Tx_Extbase_Core_Bootstrap');
            $extbaseBootstrap->initialize(array('extensionName' => $extensionName, 'pluginName' => $pluginName));
        }

        return $objectManager->get('Tx_Extbase_MVC_Web_Routing_UriBuilder');
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

        $objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
        $extensionService = $objectManager->get('Tx_Extbase_Service_ExtensionService');
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

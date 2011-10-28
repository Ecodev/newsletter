<?php

/* * ************************************************************* 
 *  Copyright notice 
 * 
 *  (c) 2006-2008 Daniel Schledermann <daniel@schledermann.net> 
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
require_once(t3lib_extMgm::extPath('newsletter') . 'class.tx_newsletter_mailer.php');

/**
 * Toolbox for newsletter and dependant extensions.
 *
 * @static
 */
abstract class tx_newsletter_tools {
	
	/**
	 * Get a newsletter-conf-template parameter
	 *
	 * @param    string   Parameter key
	 * @return   mixed    Parameter value
	 */
	public static function confParam($key) {
		if (!is_array($GLOBALS['NEWSLETTER_CONF'])) {
			$GLOBALS['NEWSLETTER_CONF'] = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['newsletter']);
		}

		return $GLOBALS['NEWSLETTER_CONF'][$key];
	}

	/**
	 * Create a configured mailer from a newsletter page record.
	 * This mailer will have both plain and html content applied as well as files attached.
	 *
	 * @param    Tx_Newsletter_Domain_Model_Newsletter       Page record.
	 * @return   object      tx_newsletter_mailer object preconfigured for sending.
	 */
	public static function getConfiguredMailer(Tx_Newsletter_Domain_Model_Newsletter $newsletter, $lang = '') {
		// Configure the mailer
		$mailer = new tx_newsletter_mailer();
		$mailer->setNewsletter($newsletter, $lang);

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
	static public function createAllSpool($onlyTest = false) {
		$newsletterRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_NewsletterRepository');
		
		$newsletters = $newsletterRepository->findAllReadyToSend($onlyTest);
		foreach ($newsletters as $newsletter)
		{
			tx_newsletter_tools::createSpool($newsletter);
		}
	}

	/**
	 * Spool a newsletter page out to the real receivers.
	 * 
	 * @param   array        Newsletter record.
	 * @param   integer      Actual begin time. 
	 * @return  void
	 */
	static public function createSpool(Tx_Newsletter_Domain_Model_Newsletter $newsletter) {
		global $TYPO3_DB;

		// If newsletter is locked because spooling now, or already spooled, then skip
		if ($newsletter->getBeginTime())
			return;

		$newsletterRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_NewsletterRepository');

		// Lock the newsletter by setting its begin_time
		$begintime = new DateTime();
		$newsletter->setBeginTime($begintime);
		$newsletterRepository->updateNow($newsletter);

		/* Get the servers */
		$hosts = array_map('trim', explode(',', tx_newsletter_tools::confParam('lb_hosts')));

		$recipientList = $newsletter->getRecipientList();
		$recipientList->init();
		while ($receiver = $recipientList->getRecipient()) {
			if (!$host = current($hosts)) {
				reset($hosts);
				$host = current($hosts);
			}
			next($hosts);

			// Register the receiver
			if (t3lib_div::validEmail($receiver['email'])) {
				$TYPO3_DB->exec_INSERTquery('tx_newsletter_domain_model_email', array(
					'pid' => $newsletter->getPid(),
					'recipient_address' => $receiver['email'],
					'recipient_data' => serialize($receiver),
					'host' => $host,
					'pid' => $newsletter->getPid(),
					'newsletter' => $newsletter->getUid(),
				));
			}
		}

		// Schedule repeated newsletter if any
		$newsletter->scheduleNextNewsletter();

		// Unlock the newsletter by setting its end_time
		$newsletter->setEndTime(new DateTime());
		$newsletterRepository->updateNow($newsletter);
	}

	/**
	 * Run the spool on a server.
	 * 
	 * @param boolean $onlyTest if true only test newsletter will be used, otherwise all (included tests)
	 * @return  integer	Number of emails sent.
	 */
	public static function runSpoolOneAll($onlyTest = false) {
		global $TYPO3_DB;

		if ($onlyTest)
			$onlyTest = 'AND is_test = 1 ';
		else
			$onlyTest = ' ';


		/* Get the machines hostname.. it can be supplied on the commandline, or we read the hostname.
		  This does absolutely only work on Unix machines without safe_mode */
		if ($_SERVER['argv'][1]) {
			$hostname = $_SERVER['argv'][1];
		} else {
			$hostname = trim(exec('hostname'));
		}

		/* Try to detect if a spool is already running
		  If there is no records for the last 15 seconds, previous spool session is assumed to have ended.
		  If there are newer records, then stop here, and assume the running mailer will take care of it.
		 */
		$rs = $TYPO3_DB->sql_query('SELECT COUNT(uid) FROM tx_newsletter_domain_model_email WHERE end_time > ' . (time() - 15)
						. " AND (host = '$hostname' OR host = '')");

		list($num_records) = $TYPO3_DB->sql_fetch_row($rs);
		if ($num_records <> 0) {
			return;
		}

		/* Do we any limit to this session? */
		if ($mails_per_round = tx_newsletter_tools::confParam('mails_per_round')) {
			$limit = " LIMIT 0, $mails_per_round ";
		}

		/* Find the receivers, select userdata, uid of target, uid of page, uid of logrecord */
		$rs = $TYPO3_DB->sql_query("SELECT tx_newsletter_domain_model_newsletter.uid, tx_newsletter_domain_model_email.uid 
						FROM tx_newsletter_domain_model_email 
						LEFT JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid) 
						WHERE (host = '$hostname' OR host = '')
						AND tx_newsletter_domain_model_email.begin_time = 0
						$onlyTest
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
	 * @return    void
	 */
	static public function runSpoolOne(Tx_Newsletter_Domain_Model_Newsletter $newsletter) {
		global $TYPO3_DB;


		/* Do we any limit to this session? */
		if ($mails_per_round = tx_newsletter_tools::confParam('mails_per_round')) {
			$limit = " LIMIT 0, $mails_per_round ";
		}

		/* Find the receivers, select userdata, uid of target, uid of page, uid of logrecord */
		$rs = $TYPO3_DB->sql_query("SELECT tx_newsletter_domain_model_newsletter.uid, tx_newsletter_domain_model_email.uid 
						FROM tx_newsletter_domain_model_email 
						LEFT JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid) 
						WHERE host = ''
						AND tx_newsletter_domain_model_newsletter.uid = " . $newsletter->getUid() . "
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
	 * @param   resource      SQL-resultset from a select from tx_newsletter_domain_model_email
	 * @return  void
	 */
	private static function runSpool($rs) {
		global $TYPO3_DB;

		// We will log newsletters progress to the syslog daemon
		openlog('newsletter', LOG_ODELAY, LOG_MAIL);
		$numberOfMails = 0;
		$mailers = array();

		$newsletterRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_NewsletterRepository');
		$emailRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_EmailRepository');

		$oldNewsletterUid = null;
		while (list($newsletterUid, $emailUid) = $TYPO3_DB->sql_fetch_row($rs)) {

			$email = $emailRepository->findByUid($emailUid);

			// Mark it as started sending
			$email->setBeginTime(new DateTime());
			$emailRepository->updateNow($email);

			/* For the page, this way we can support multiple pages in one spool session */
			if ($newsletterUid != $oldNewsletterUid) {
				$oldNewsletterUid = $newsletterUid;
				$mailers = array();

				$newsletter = $newsletterRepository->findByUid($newsletterUid);
			}

			// Define the language of email
			$recipientData = $email->getRecipientData();
			$L = $recipientData['L'];

			// Was a language with this page defined, if not create one 
			if (!is_object($mailers[$L])) {
				$mailers[$L] = &tx_newsletter_tools::getConfiguredMailer($newsletter, $L);
			}

			// Send the email
			$mailers[$L]->send($email);

			// Mark it as sent already
			$email->setEndTime(new DateTime());
			$emailRepository->updateNow($email);

			$numberOfMails++;
		}

		/* Log numbers to syslog */
		syslog(LOG_INFO, "Sending $numberOfMails mails from " . $_SERVER['argv'][0]);
		closelog();
	}

}


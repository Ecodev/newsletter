<?php
/*************************************************************** 
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
***************************************************************/
 
require_once(PATH_t3lib.'class.t3lib_extmgm.php');
require_once(PATH_t3lib.'class.t3lib_befunc.php');
require_once(t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_mailer.php'); 
foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newsletter']['includeClassFiles'] as $file) {
	require_once($file);
}
 
/**
 * Toolbox for newsletter and dependant extensions.
 *
 * @static
 */ 

class tx_newsletter_tools {
	/**
	  * Get a newsletter-conf-template parameter
	  *
	  * @param    string   Parameter key
	  * @return   mixed    Parameter value
	  */
	function confParam($key) {
		if (!is_array($GLOBALS['NEWSLETTER_CONF'])) {
			$GLOBALS['NEWSLETTER_CONF'] = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['newsletter']);
		}
       
		return $GLOBALS['NEWSLETTER_CONF'][$key];
	}
    
	/**
	 * Get dynamic TYPO3 content in a safe way.
	 *
	 * This is just a wrapper to t3lib_div::getURL that will abort with a silent die, if the content seems strange. This is to prevent 
	 * error-filled content being sent to the receivers. This is only used for fetching dynamic text or html content with. It should *not*
	 * be used to fetch CSS, images or other static content.
	 * 
	 * @param   string   URL of content
	 * @return  string   Returned data. 
	 * 
	 */
	function getURL($url) {
		$content = t3lib_div::getURL($url);
       
		/* Content should be more that just a few characters. Apache error propably occured */
		if (strlen($content) < 200) {
			die ("TC Newsletter failure ($url): Content too short. The content must be at least 200 chars long to be considered valid.");
		}		
	       
		/* Content should not contain PHP-Warnings */
		if (substr($content, 0, 22) == "<br />\n<b>Warning</b>:") {
			die ("TC Newsletter failure ($url): Content contains PHP Warnings. This must not reach the receivers.");
		}
       
		/* Content should not contain PHP-Warnings */
		if (substr($content, 0, 26) == "<br />\n<b>Fatal error</b>:") {
			die ("TC Newsletter failure ($url): Content contains PHP Fatal errors. This must not reach the receivers.");
		}
       
		/* If the page contains a "Pages is being generared" text... this is bad too */
		if (strpos($content, 'Page is being generated.') && strpos($content, 'If this message does not disappear within')) {
			die ("TC Newsletter failure ($url): Content contains \"wait\" signatures. This must not reach the receivers."); 
		}
       
		return $content;
	}

	/**
	* Function to fetch the proper domain from with to fetch content for newsletter.
	* This is either a sys_domain record from the page tree or the fetch_path property.
	*
	* @param    array       Record of page to get the correct domain for.
	* @return   string      Correct domain.
	*/
	function getDomainForPage($p) {
		global $TYPO3_DB;

		/* Is anything hardcoded from TYPO3_CONF_VARS? */
		if ($fetchPath = tx_newsletter_tools::confParam('fetch_path')) {
			return $fetchPath;
		}

		/* Else we try to resolve a domain */

		/* What pages to search */
		$pids = array_reverse(t3lib_befunc::BEgetRootLine($p['uid']));

		foreach ($pids as $page) {
			/* Domains */
			$rs = $TYPO3_DB->sql_query("SELECT domainName FROM sys_domain
						    INNER JOIN pages ON sys_domain.pid = pages.uid
						    WHERE NOT sys_domain.hidden
						    AND NOT pages.hidden
						    AND NOT pages.deleted
						    AND pages.uid = $page[uid]
						    ORDER BY sys_domain.sorting
						    LIMIT 0,1");

			if ($TYPO3_DB->sql_num_rows($rs)) {
				list($domain) = $TYPO3_DB->sql_fetch_row($rs);
			}
		}

		return $domain;
	}

	/**
	* Gets the correct sendername for a newsletter.
	* This is either:
	* The sender name defined on the page record.
	* or the sender name defined in $TYPO3_CONF_VARS['EXTCONF']['newsletter']['senderName']
	* or The sites name as defined in $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename']
	*
	* @param    array       Record of the newsletter page
	* @return   string      The sender name
	*/
	function getSenderForPage ($p) {
		global $TYPO3_DB;

		/* The sender defined on the page? */
		if ($p['tx_newsletter_sendername']) {
			return $p['tx_newsletter_sendername'];
		}

		/* Anything in typo3_conf_vars? */
		$sender = tx_newsletter_tools::confParam('sender_name');
		if ($sender == 'user') {
			/* Use the page-owner as user */
			$rs = $GLOBALS['TYPO3_DB']->sql_query("SELECT realName 
							  FROM be_users bu
							  LEFT JOIN pages p ON bu.uid = p.perms_userid
							  WHERE p.uid = $p[uid]");

			list($sender) = $GLOBALS['TYPO3_DB']->sql_fetch_row($rs);
			if ($sender) {
				return $sender;
			}
		}

		/* Maybe it was a specifies name */
		if ($sender && $sender != 'user') {
			return $sender;
		}

		/* If none of above, just use the sitename */
		return $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'];
	}

	/**
	* Gets the correct sender email address for a newsletter.
	* This is either:
	* The sender email address defined on the page record.
	* or the email address (if any) of the be_user owning the page.
	* or the email address defined in extConf
	* or the guessed email address of the user running the this process.
	* or the no-reply@$_SERVER['HTTP_HOST'].
	*
	* @param    array       Record of the newsletter page
	* @return   string      The sender email
	*/
	function getEmailForPage ($p) {
		global $TYPO3_DB;

		/* The sender defined on the page? */
		if (t3lib_div::validEmail($p['tx_newsletter_senderemail'])) {
			return $p['tx_newsletter_senderemail'];
		}

		/* Anything in typo3_conf_vars? */
		$email = tx_newsletter_tools::confParam('sender_email');        
		if ($email == 'user') {
			/* Use the page-owner as user */
			$rs = $GLOBALS['TYPO3_DB']->sql_query("SELECT email 
			FROM be_users bu
			LEFT JOIN pages p ON bu.uid = p.perms_userid
			WHERE p.uid = $p[uid]");

			list($email) = $GLOBALS['TYPO3_DB']->sql_fetch_row($rs);
			if (t3lib_div::validEmail($email)) {
				return $email;
			}
		}

		/* Maybe it was a hardcoded email address? */
		if (t3lib_div::validEmail($email)) {
			return $email;
		}

		/* If this did not yield an email address, try to use the system-user */
		if( ini_get('safe_mode') || TYPO3_OS == 'WIN'){
			return  "no-reply@".$_SERVER['HTTP_HOST'];
		}

		return  trim(exec('whoami')).'@'.trim(exec('hostname'));
	}

	/**
	* Get the bounce address for the mail 
	*
	* @param   array     Record of the mail page 
	* @return  sting     Email address to collect bounces
	*/
	function getBounceAddressForPage($page) {
		global $TYPO3_DB;

		$rs = $TYPO3_DB->exec_SELECTquery('email', 'tx_newsletter_domain_model_bounceaccount', "uid = $page[tx_newsletter_bounceaccount]");
		if (list($address) =$TYPO3_DB->sql_fetch_row($rs)) {
			return $address;
		} else {
			return '';
		}
	}

	/**
	* Update a newsletter with a new schedule.
	*
	* @param    array      Page record.
	* @return   void
	*/
	function setScheduleAfterSending ($page) {
		global $TYPO3_DB;

		$senttime = $page['tx_newsletter_senttime'];

		switch ($page['tx_newsletter_repeat']) {
			case 0: $newtime = 0; break;
			case 1: $newtime = 86400 + $senttime; break;
			case 2: $newtime = 7 * 86400 + $senttime; break;
			case 3: $newtime = 14 * 86400 + $senttime; break;
			case 4: list($year, $month, $day, $hour, $minute) = explode('-', date("Y-n-j-G-i", $senttime));
				$month += 1;
				$newtime = mktime ($hour, $minute, 0, $month, $day, $year);
				break;
			case 5: list($year, $month, $day, $hour, $minute) = explode('-', date("Y-n-j-G-i", $senttime));
				$month += 3;
				$newtime = mktime ($hour, $minute, 0, $month, $day, $year);
				break;
			case 6: list($year, $month, $day, $hour, $minute) = explode('-', date("Y-n-j-G-i", $senttime));
				$month += 6;
				$newtime = mktime ($hour, $minute, 0, $month, $day, $year);
				break;
			case 7: list($year, $month, $day, $hour, $minute) = explode('-', date("Y-n-j-G-i", $senttime));
				$year += 1;
				$newtime = mktime ($hour, $minute, 0, $month, $day, $year);
				break;
		}

		$TYPO3_DB->exec_UPDATEquery('pages', "uid = $page[uid]", array('tx_newsletter_senttime' => $newtime));
	}

       
	/**
	* Create a configured mailer from a newsletter page record.
	* This mailer will have both plain and html content applied as well as files attached.
	*
	* @param    array       Page record.
	* @return   object      tx_newsletter_mailer object preconfigured for sending.
	*/
	public static function getConfiguredMailer($page, $lang = '') {
		$append_url = tx_newsletter_tools::confParam('append_url');

		/* Any language defined? */
		
		/** 
		 * 12.09.2008 mads@brunn.dk
		 * L-param is set even if it's '0' 
		 * Needed in those cases where default language in frontend and backend differs
		 */ 
		if ($lang<>-1 && $lang<>"") {
			$lang = "&L=$lang";
		}
		
		/* Configure the mailer */
		$mailer = new tx_newsletter_mailer();
		$domain = tx_newsletter_tools::getDomainForPage($page);
		$mailer->siteUrl = "http://$domain/";
		$mailer->homeUrl = "http://$domain/".t3lib_extMgm::siteRelPath('newsletter');
		$mailer->senderName = tx_newsletter_tools::getSenderForPage($page);
		$mailer->senderEmail = tx_newsletter_tools::getEmailForPage($page);
		$mailer->bounceAddress = tx_newsletter_tools::getBounceAddressForPage($page);
		$mailer->setTitle($page['title']);
		$url = "http://$domain/index.php?id=$page[uid]&no_cache=1$lang$append_url";
		$mailer->setHtml(tx_newsletter_tools::getURL($url));

		/* Construct plaintext */
		$plain = tx_newsletter_plain::loadPlain($page, $mailer->domain);
		switch ($plain->fetchMethod) {
			case 'src' :  $plain->setHtml($mailer->html); break;
			case 'url' :  $plain->setHtml($url); break;
		}
		$mailer->setPlain($plain->getPlaintext());

		/* Attaching files */
		$files = explode (',', $page['tx_newsletter_attachfiles']);
		foreach ($files as $file) {
			if (trim($file) != '') {
				$file = PATH_site."uploads/tx_newsletter/$file";
				$mailer->addAttachment($file);
			}
		}

		/* hook for modifing the mailer before finish preconfiguring */
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newsletter']['getConfiguredMailerHook'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newsletter']['getConfiguredMailerHook'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$mailer = $_procObj->getConfiguredMailerHook($mailer, $page);
			}
		}

		/* Done preconfiguring mailer */
		return $mailer;    
	}

	/**
	* Send a newsletter page out to the test receivers.
	* 
	* @param    array      Page record.
	* @param    array      List with receivers. If this is provided only the receivers in the list will be mailed.
	* @return   void
	*/
	function mailForTest($page, $onlyReceivers = array()) {
		global $TYPO3_DB;
		$mailers = array();

		/* Find the users */
		$target = tx_newsletter_target::loadTarget($page['tx_newsletter_test_target']);

		/* Only the provided users? */
		/* Hmm  this needs to be resolved. We now only send out to selected users */ 
		if (count($onlyReceivers)) {
			$targetdata = array();
			/* Then get the record, and replace the target with a new one, containing only this user */
			while ($receiver = $target->getRecord()) {
				if (in_array($receiver['email'], $onlyReceivers)) {
					$targetdata[] = $receiver;
				}
			}

			$target = new tx_newsletter_target_array();
			$target->data = $targetdata;
			$target->resetTarget();
		}

		while ($receiver = $target->getRecord()) {
			if (t3lib_div::validEmail($receiver['email'])) {
				/* We need to use a mailer with the correct language */
				$L = $receiver['L'];
				if (! is_object($mailers[$L])) {
					$mailers[$L] = &tx_newsletter_tools::getConfiguredMailer($page, $L);
				}	

				$mailers[$L]->send($receiver, array(
					'testClickLinks' => $page['tx_newsletter_register_clicks'],
					'testSpy' => $page['tx_newsletter_spy'],
				));
			}
		}
	}

	/**
	 * Spool a newsletter page out to the real receivers.
	 * 
	 * @param   array        Page record.
	 * @param   integer      Actual begin time. 
	 * @return  void
	 */
	function createSpool($page, $begintime) {
		global $TYPO3_DB;

		/* Find the receivers */
		$targets = explode(',',$page['tx_newsletter_real_target']);

		/* Get the servers */
		$hosts = array_map('trim', explode(',', tx_newsletter_tools::confParam('lb_hosts')));

		foreach ($targets as $target_uid) {
			$target = tx_newsletter_target::loadTarget($target_uid);
			$target->startReal();
      
			while ($receiver = $target->getRecord()) {
				if (!$host = current($hosts)) {
					reset($hosts);
					$host = current($hosts);
				}
				next($hosts);
          
				/* Register the receiver */
				if (t3lib_div::validEmail($receiver['email'])) {
					$TYPO3_DB->exec_INSERTquery('tx_newsletter_domain_model_emailqueue', array(   
						'receiver' => $receiver['email'],
						'user_uid' => $receiver['uid'], 
						'begintime' => $begintime,
						'sendtime' => 0,
						'userdata' => serialize($receiver),
						'host' => $host,
						'authcode' => $receiver['authCode'],
						'target' => $target_uid,
						'pid' => $page['uid']));
				}
			}
			$target->endReal();
		}
	}
    
	/**
	 * Run the spool on a server.
	 * 
	 * @return  integer	Number of emails sent.
	 */
	function runSpool() {
		global $TYPO3_DB;

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
		$rs = $TYPO3_DB->sql_query('SELECT COUNT(uid) FROM tx_newsletter_domain_model_emailqueue WHERE sendtime > '.(time() - 15)
                	                     ." AND (host = '$hostname' OR host = '')");
                                     
		list($num_records) = $TYPO3_DB->sql_fetch_row($rs);
		if ($num_records <> 0) {
			return;
		}
       
		/* Do we any limit to this session? */
		if ($mails_per_round = tx_newsletter_tools::confParam('mails_per_round')) {
			$limit = " LIMIT 0, $mails_per_round ";
		}

		/* Find the receivers, select userdata, uid of target, uid of page, uid of logrecord */
		$rs = $TYPO3_DB->sql_query("SELECT userdata, target, pid, uid 
						FROM tx_newsletter_domain_model_emailqueue 
						WHERE (host = '$hostname' OR host = '')
						AND sendtime = 0
						ORDER BY pid ".$limit); 

		/* Do it, if there is any records */
		if ($numRows = $TYPO3_DB->sql_num_rows($rs)) {
			tx_newsletter_tools::_runSpool($rs);
		}

		return $numRows;
	}
    
	/** 
	 * Run the spool from a browser 
	 * This has some limitations. No load balance. Different permissions. And should have a mails_per_round-value
	 *
	 * @return    void
	 */
	function runSpoolInteractive() {
		global $TYPO3_DB;
		$id = intval($_REQUEST['id']);
       
		/* Do we any limit to this session? */
		if ($mails_per_round = tx_newsletter_tools::confParam('mails_per_round')) {
			$limit = " LIMIT 0, $mails_per_round ";
		}

		/* Find the receivers, select userdata, uid of target, uid of page, uid of logrecord */
		$rs = $TYPO3_DB->sql_query("SELECT userdata, target, pid, uid 
						FROM tx_newsletter_domain_model_emailqueue 
						WHERE host = ''
						AND pid = $id
						AND sendtime = 0
						ORDER BY pid ".$limit);

		/* Do it, if there is any records */
		if ($numRows = $TYPO3_DB->sql_num_rows($rs)) {
			tx_newsletter_tools::_runSpool($rs);
		}

		return $numRows;
	}  
    
	/** 
	 * Method that accually runs the spool
	 *
	 * @param   resource      SQL-resultset from a select from tx_newsletter_domain_model_emailqueue
	 * @return  void
	 */
	function _runSpool ($rs) {
		global $TYPO3_DB;

		/* We will log newsletters progress to the syslog daemon */
		openlog ('newsletter', LOG_ODELAY, LOG_MAIL);
		$numberOfMails = 0;
		$mailers = array();

		while (list($userdata, $target, $pid, $sendid) = $TYPO3_DB->sql_fetch_row($rs)) {
			/* Unpack the real user data */
			$receiver = unserialize($userdata);
			$L = $receiver['L'];

			/* For the page, this way we can support multiple pages in one spool session */   
			if ($pid <> $old_pid) {
				$old_pid = $pid;
				$mailers = array();
				$rs_p = $TYPO3_DB->exec_SELECTquery('*', 'pages', "uid = $pid");
				$page = $TYPO3_DB->sql_fetch_assoc($rs_p);
			}

			/* Was a language with this page defined, if not create one */ 
			if (!is_object($mailers[$L])) {
				$mailers[$L] = &tx_newsletter_tools::getConfiguredMailer($page, $L); 
			}

			/* Mark it as send already */
			$TYPO3_DB->exec_UPDATEquery('tx_newsletter_domain_model_emailqueue', "uid = $sendid", array('sendtime' => time()));
           
			/* Give it the stamp */
			if ($receiver['uid'] && $receiver['authCode']) {
				$infoHeaders = array('X-newsletter-info' => "//$pid/$target/$receiver[uid]/$receiver[authCode]/$sendid//");
			} else {
				$infoHeaders = array();
			}
            
			/* Should we register what links have been clicked? */
			if ($page['tx_newsletter_register_clicks']) {				
				$mailers[$L]->send($receiver, array(
						'insertSpy' => $page['tx_newsletter_spy'],
						'makeClickLinks' => true,
						'authCode' => $receiver['authCode'],
						'sendid' => $sendid,
					)
				);
				
            
				
			} else {
				/* Do the send */
				$mailers[$L]->send ($receiver, $infoHeaders);
			}

			$numberOfMails++;
		}    

		/* Log numbers to syslog */
		syslog (LOG_INFO, "Sending $numberOfMails mails from ".$_SERVER['argv'][0]);
		closelog();
	}  
}

?>

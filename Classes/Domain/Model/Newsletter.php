<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 3 of the License, or
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


/**
 * Newsletter represents a page to be sent to a specific time to several recipients.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Tx_Newsletter_Domain_Model_Newsletter extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * When the newsletter will start sending emails
	 *
	 * @var DateTime $plannedTime
	 * @validate NotEmpty
	 */
	protected $plannedTime;

	/**
	 * beginTime
	 *
	 * @var DateTime $beginTime
	 */
	protected $beginTime;

	/**
	 * endTime
	 *
	 * @var DateTime $endTime
	 */
	protected $endTime;

	/**
	 * 0-7 values to indicates when this newsletter will repeat
	 *
	 * @var integer $repetition
	 */
	protected $repetition;

	/**
	 * Tool used to convert to plain text
	 *
	 * @var string $plainConverter
	 */
	protected $plainConverter;

	/**
	 * Whether this newsletter is for test purpose. If it is it will be ignored in statistics
	 *
	 * @var boolean $isTest
	 * @validate NotEmpty
	 */
	protected $isTest;

	/**
	 * List of files to be attached (comma separated list
	 *
	 * @var string $attachments
	 */
	protected $attachments;

	/**
	 * The name of the newsletter sender
	 *
	 * @var string $senderName
	 * @validate NotEmpty
	 */
	protected $senderName;

	/**
	 * The email of the newsletter sender
	 *
	 * @var string $senderEmail
	 * @validate NotEmpty
	 */
	protected $senderEmail;

	/**
	 * injectOpenSpy
	 *
	 * @var boolean $injectOpenSpy
	 */
	protected $injectOpenSpy;

	/**
	 * injectLinksSpy
	 *
	 * @var boolean $injectLinksSpy
	 */
	protected $injectLinksSpy;

	/**
	 * bounceAccount
	 *
	 * @var Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount
	 */
	protected $bounceAccount;

	/**
	 * recipientList
	 *
	 * @var Tx_Newsletter_Domain_Model_RecipientList $recipientList
	 */
	protected $recipientList;

	/**
	 * Constructor
	 */
	public function __construct() {
		// Set default values for new newsletter
		$this->setPlainConverter('Tx_Newsletter_Domain_Model_PlainConverter_Builtin');
		$this->setRepetition(0);
		$this->setPlannedTime(new DateTime());
	}

	/**
	 * Setter for plannedTime
	 *
	 * @param DateTime $plannedTime When the newsletter will start sending emails
	 * @return void
	 */
	public function setPlannedTime(DateTime $plannedTime) {
		$this->plannedTime = $plannedTime;
	}

	/**
	 * Getter for plannedTime
	 *
	 * @return DateTime When the newsletter will start sending emails
	 */
	public function getPlannedTime() {
		return $this->plannedTime;
	}

	/**
	 * Setter for beginTime
	 *
	 * @param DateTime $beginTime beginTime
	 * @return void
	 */
	public function setBeginTime(DateTime $beginTime) {
		$this->beginTime = $beginTime;
	}

	/**
	 * Getter for beginTime
	 *
	 * @return DateTime beginTime
	 */
	public function getBeginTime() {
		return $this->beginTime;
	}

	/**
	 * Setter for endTime
	 *
	 * @param DateTime $endTime endTime
	 * @return void
	 */
	public function setEndTime(DateTime $endTime) {
		$this->endTime = $endTime;
	}

	/**
	 * Getter for endTime
	 *
	 * @return DateTime endTime
	 */
	public function getEndTime() {
		return $this->endTime;
	}

	/**
	 * Setter for repetition
	 *
	 * @param integer $repetition 0-7 values to indicates when this newsletter will repeat
	 * @return void
	 */
	public function setRepetition($repetition) {
		$this->repetition = $repetition;
	}

	/**
	 * Getter for repetition
	 *
	 * @return integer 0-7 values to indicates when this newsletter will repeat
	 */
	public function getRepetition() {
		return $this->repetition;
	}

	/**
	 * Setter for plainConverter
	 *
	 * @param string $plainConverter Tool used to convert to plain text
	 * @return void
	 */
	public function setPlainConverter($plainConverter) {
		$this->plainConverter = $plainConverter;
	}

	/**
	 * Getter for plainConverter
	 *
	 * @return string Tool used to convert to plain text
	 */
	public function getPlainConverter() {
		return $this->plainConverter;
	}
	
	/**
	 * Returns an instance of plain converter
	 * @throws Exception
	 * @return Tx_Newsletter_Domain_Model_IPlainConverter
	 */
	public function getPlainConverterInstance()
	{
		$class = $this->getPlainConverter();
		if (!class_exists($class))
			throw new Exception("Plain text converter of class '$class' not found");
			
		$converter = new $class();
		 
		if (!($converter instanceof Tx_Newsletter_Domain_Model_IPlainConverter))
			throw new Exception("$class does not implement Tx_Newsletter_Domain_Model_IPlainConverter");
			
		return $converter;
	}

	/**
	 * Setter for isTest
	 *
	 * @param boolean $isTest Whether this newsletter is for test purpose. If it is it will be ignored in statistics
	 * @return void
	 */
	public function setIsTest($isTest) {
		$this->isTest = $isTest;
	}

	/**
	 * Getter for isTest
	 *
	 * @return boolean Whether this newsletter is for test purpose. If it is it will be ignored in statistics
	 */
	public function getIsTest() {
		return $this->isTest;
	}

	/**
	 * Returns the state of isTest
	 *
	 * @return boolean the state of isTest
	 */
	public function isIsTest() {
		return $this->getIsTest();
	}

	/**
	 * Setter for attachments
	 *
	 * @param string $attachments List of files to be attached (comma separated list
	 * @return void
	 */
	public function setAttachments($attachments) {
		$this->attachments = join(',', $attachments);
	}

	/**
	 * Getter for attachments
	 *
	 * @return string List of files to be attached (comma separated list
	 */
	public function getAttachments() {
		return explode(',', $this->attachments);
	}

	/**
	 * Setter for senderName
	 *
	 * @param string $senderName The name of the newsletter sender
	 * @return void
	 */
	public function setSenderName($senderName) {
		$this->senderName = $senderName;
	}

	/**
	 * Gets the correct sendername for a newsletter.
	 * This is either:
	 * The sender name defined on the newsletter record.
	 * or the sender name defined in $TYPO3_CONF_VARS['EXTCONF']['newsletter']['senderName']
	 * or The sites name as defined in $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename']
	 *
	 * @return string The name of the newsletter sender
	 */
	public function getSenderName() {
		global $TYPO3_DB;

		// Return the senderName defined on the newsletter
		if ($this->senderName) {
			return $this->senderName;
		}
		
		// Return the senderName defined in extension configuration
		$sender = tx_newsletter_tools::confParam('sender_name');
		if ($sender == 'user')
		{
			// Use the page-owner as user
			$rs = $GLOBALS['TYPO3_DB']->sql_query("SELECT realName 
							  FROM be_users
							  LEFT JOIN pages ON be_users.uid = pages.perms_userid
							  WHERE pages.uid = $this->pid");

			list($sender) = $GLOBALS['TYPO3_DB']->sql_fetch_row($rs);
			if ($sender) {
				return $sender;
			}
		}
		// Returns the name as defined in configuration
		elseif ($sender)
		{
			return $sender;
		}
		
		// If none of above, just use the sitename
		return $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'];
	}

	/**
	 * Setter for senderEmail
	 *
	 * @param string $senderEmail The email of the newsletter sender
	 * @return void
	 */
	public function setSenderEmail($senderEmail) {
		$this->senderEmail = $senderEmail;
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
	 * @return string The email of the newsletter sender
	 */
	public function getSenderEmail() {
		global $TYPO3_DB;

		/* The sender defined on the page? */
		if (t3lib_div::validEmail($this->senderEmail)) {
			return $this->senderEmail;
		}

		/* Anything in typo3_conf_vars? */
		$email = tx_newsletter_tools::confParam('sender_email');        
		if ($email == 'user') {
			/* Use the page-owner as user */
			$rs = $GLOBALS['TYPO3_DB']->sql_query("SELECT email 
			FROM be_users bu
			LEFT JOIN pages p ON bu.uid = p.perms_userid
			WHERE p.uid = $this->pid");

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
	 * Setter for injectOpenSpy
	 *
	 * @param boolean $injectOpenSpy injectOpenSpy
	 * @return void
	 */
	public function setInjectOpenSpy($injectOpenSpy) {
		$this->injectOpenSpy = $injectOpenSpy;
	}

	/**
	 * Getter for injectOpenSpy
	 *
	 * @return boolean injectOpenSpy
	 */
	public function getInjectOpenSpy() {
		return $this->injectOpenSpy;
	}

	/**
	 * Returns the state of injectOpenSpy
	 *
	 * @return boolean the state of injectOpenSpy
	 */
	public function isInjectOpenSpy() {
		return $this->getInjectOpenSpy();
	}

	/**
	 * Setter for injectLinksSpy
	 *
	 * @param boolean $injectLinksSpy injectLinksSpy
	 * @return void
	 */
	public function setInjectLinksSpy($injectLinksSpy) {
		$this->injectLinksSpy = $injectLinksSpy;
	}

	/**
	 * Getter for injectLinksSpy
	 *
	 * @return boolean injectLinksSpy
	 */
	public function getInjectLinksSpy() {
		return $this->injectLinksSpy;
	}

	/**
	 * Returns the state of injectLinksSpy
	 *
	 * @return boolean the state of injectLinksSpy
	 */
	public function isInjectLinksSpy() {
		return $this->getInjectLinksSpy();
	}

	/**
	 * Setter for bounceAccount
	 *
	 * @param Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount bounceAccount
	 * @return void
	 */
	public function setBounceAccount(Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount = null) {
		$this->bounceAccount = $bounceAccount;
	}

	/**
	 * Getter for bounceAccount's UID
	 *
	 * @return integer uidBounceAccount
	 */
	public function getUidBounceAccount() {
		$bounceAccount = $this->getBounceAccount();
		if ($bounceAccount)
			return $bounceAccount->getUid();
		else
			return null;
	}
	
	/**
	 * Getter for bounceAccount
	 *
	 * @return Tx_Newsletter_Domain_Model_BounceAccount bounceAccount
	 */
	public function getBounceAccount() {
		return $this->bounceAccount;
	}

	/**
	 * Setter for recipientList
	 *
	 * @param Tx_Newsletter_Domain_Model_RecipientList $recipientList recipientList
	 * @return void
	 */
	public function setRecipientList(Tx_Newsletter_Domain_Model_RecipientList $recipientList) {
		$this->recipientList = $recipientList->uid;
	}

	/**
	 * Getter for recipientList
	 *
	 * @return Tx_Newsletter_Domain_Model_RecipientList recipientList
	 */
	public function getRecipientList() {
		return $this->recipientList;
	}
	
	/**
	 * Getter for recipientList's UID
	 *
	 * @return integer uidRecipientList
	 */
	public function getUidRecipientList() {
		$recipientList = $this->getRecipientList();
		if ($recipientList)
			return $recipientList->getUid();
		else
			return null;
	}
	
	
	/**
	 * Getter for recipientList
	 *
	 * @return Tx_Newsletter_Domain_Model_RecipientList recipientList
	 */
	public function getRecipientListConcreteInstance() {
		if (!$this->getRecipientList())
			return null;
			
		// TODO cleanup instanciation process for recipientList, see as well self:setRecipientList, RecipientList::getTarget() and RecipientList::loadTarget()
		return Tx_Newsletter_Domain_Model_RecipientList::loadTarget($this->getRecipientList()->getUid());
	}
	
	/**
	 * Function to fetch the proper domain from with to fetch content for newsletter.
	 * This is either a sys_domain record from the page tree or the fetch_path property.
	 *
	 * @return   string      Correct domain.
	 */
	public function getDomain()
	{
		global $TYPO3_DB;

		// Is anything hardcoded from TYPO3_CONF_VARS ?
		if ($fetchPath = tx_newsletter_tools::confParam('fetch_path')) {
			return $fetchPath;
		}

		// Else we try to resolve a domain

		/* What pages to search */
		$pids = array_reverse(t3lib_befunc::BEgetRootLine($this->pid));

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
	 * Returns the title of the page sent by this newsletter
	 * @return string the title
	 */
	function getTitle()
	{
		global $TYPO3_DB;
		$rs = $TYPO3_DB->sql_query("SELECT title FROM pages WHERE uid = $this->pid");

		$title = '';
		if ($TYPO3_DB->sql_num_rows($rs)) {
			list($title) = $TYPO3_DB->sql_fetch_row($rs);
		}
		
		return $title;
	}
	

	/**
	 * Schedule the next newsletter if it defined to be repeated
	 */
	public function scheduleNextNewsletter()
	{
		$plannedTime = $this->getPlannedTime();
		list($year, $month, $day, $hour, $minute) = explode('-', date("Y-n-j-G-i", $plannedTime));

		switch ($this->getRepetition()) {
			case 0: return;
			case 1: $day += 1; break;
			case 2: $day += 7; break;
			case 3: $day += 14; break;
			case 4: $month += 1; break;
			case 5: $month += 3; break;
			case 6: $month += 6; break;
			case 7: $year += 1; break;
		}
		$newPlannedTime = mktime ($hour, $minute, 0, $month, $day, $year);

		
		// Clone this newsletter and give the new plannedTime
		// We cannot use extbase because __clone() doesn't work and even if we clone manually the PID cannot be set
		global $TYPO3_DB;		
		$TYPO3_DB->sql_query("INSERT tx_newsletter_domain_model_newsletter 
		SELECT null AS uid, pid, '$newPlannedTime' AS planned_time, 0 AS begin_time, 0 AS end_time, repetition, plain_converter, is_test, attachments, sender_name, sender_email, inject_open_spy, inject_links_spy, bounce_account, recipient_list, " . time() . " AS tstamp, " . time() . " AS crdate, deleted, hidden
		 FROM tx_newsletter_domain_model_newsletter WHERE uid = " . $this->getUid());
		
	}
	
	/**
	 * Returns the count of recipient to which the newsletter was actually sent (or going to be sent if the process is not finished yet).
	 * This may differ from $newsletter->getRecipientListConcreteInstance()->getCount()
	 * because the recipientList may change over time.
	 */
	public function getEmailCount()
	{
		$emailRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_EmailRepository');
		return $emailRepository->getCount($this->uid);
	}
	
	/**
	 * Get the number of not yet sent email
	 */
	public function getEmailNotSentCount() {
		global $TYPO3_DB;
		
		$numberOfNotSent = $TYPO3_DB->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'end_time = 0 AND newsletter = ' . $this->getUid());

		return (int)$numberOfNotSent;
	}
	
	/**
	 * Get the number of sent email for a newsletter
	 * @global t3lib_DB $TYPO3_DB
	 * @return int count of email already sent
	 */
	public function getEmailSentCount() {
		global $TYPO3_DB;
		
		$numberOfSent = $TYPO3_DB->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'end_time != 0 AND opened = 0 AND bounced = 0 AND newsletter = ' . $this->getUid());

		return (int)$numberOfSent;
	}

	/**
	 * Get the number of opened email.
	 * Temporarily relies on TYPO3_DB. The code must be refactored towards a query against a content repository
	 * @global t3lib_DB $TYPO3_DB
	 * @return int count of opened emails
	 */
	public function getEmailOpenedCount() {
		global $TYPO3_DB;
		
		$numberOfOpened = $TYPO3_DB->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'opened = 1 AND bounced = 0 AND newsletter = ' . $this->getUid());
		
		return (int)$numberOfOpened;
	}

	/**
	 * Get the number of bounced email.
	 * Temporarily relies on TYPO3_DB. The code must be refactored towards a query against a content repository
	 *
	 * @global t3lib_DB $TYPO3_DB
	 * @return int $numberOfBounce
	 */
	public function getEmailBouncedCount() {
		global $TYPO3_DB;

		$numberOfBounce = $TYPO3_DB->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'bounced = 1 AND newsletter = ' . $this->getUid());
		
		return (int)$numberOfBounce;
	}
	
	/**
	 * Returns the URL of the content of this newsletter
	 * @return string
	 */
	public function getContentUrl($language = null) {
		$append_url = tx_newsletter_tools::confParam('append_url');
		$domain = $this->getDomain();
		
		if (!is_null($language)) {
			$language = '&L=' . $language;
		}
		
		return "http://$domain/index.php?no_cache=1&id=" . $this->getPid() . $language . $append_url;
	}
	
	/**
	 * Returns the content of this newsletter with validation messages. The content
	 * is also "fixed" automatically when possible.
	 * @global type $LANG
	 * @param string $language language of the content of the newsletter (the 'L' parameter in TYPO3 URL)
	 * @return array
	 */
	public function getValidatedContent($language = null)
	{	
		// Here we need to include the locallization file for ExtDirect calls, otherwise we get empty strings
		global $LANG;
		$LANG->includeLLFile('EXT:newsletter/Resources/Private/Language/locallang.xml');
		
		$domain = $this->getDomain();
		$content= t3lib_div::getURL($this->getContentUrl());
		
		$errors = array();
		$warnings = array();
		$infos = array();
		
		// Content should be more that just a few characters. Apache error propably occured
		if (strlen($content) < 200) {
			$errors []= "Content too short. The content must be at least 200 chars long to be considered valid.";
		}

		// Content should not contain PHP-Warnings
		if (substr($content, 0, 22) == "<br />\n<b>Warning</b>:") {
			$errors []= "Content contains PHP Warnings. This must not reach the receivers.";
		}

		// Content should not contain PHP-Warnings
		if (substr($content, 0, 26) == "<br />\n<b>Fatal error</b>:") {
			$errors []= "Content contains PHP Fatal errors. This must not reach the receivers.";
		}

		// If the page contains a "Pages is being generared" text... this is bad too
		if (strpos($content, 'Page is being generated.') && strpos($content, 'If this message does not disappear within')) {
			$errors []= "Content contains \"wait\" signatures. This must not reach the receivers.";
		}
		
		// Find linked css and convert into a style-tag
		preg_match_all('|<link rel="stylesheet" type="text/css" href="([^"]+)"[^>]+>|Ui', $content, $urls);
		foreach ($urls[1] as $i => $url) {
			$get_url = str_replace("http://$domain/", '', $url);
			$content = str_replace ($urls[0][$i],
                   "<style type=\"text/css\">\n<!--\n"
                   . t3lib_div::getURL(PATH_site . str_replace("http://$domain/", '', $url))
                   . "\n-->\n</style>", $content);
		}
		if (count($urls[1])) {
			$infos[] = $LANG->getLL('mail_contains_linked_styles');
		}

		// We cant very well have attached javascript in a newsmail ... removing
		$content = preg_replace('|<script[^>]*type="text/javascript"[^>]*>[^<]*</script>|i', '', $content, -1, $count);
		if ($count) {
			$warnings[] = $LANG->getLL('mail_contains_javascript');
		}
		
		// Fix relative links
		preg_match_all('|<a [^>]*href="(.*)"|Ui', $src, $urls);
		foreach ($urls[1] as $i => $url) {
			// If this is already a absolute link, dont replace it
			if (!preg_match('|^http://|', $url) && !preg_match('|^mailto:|', $url) && !preg_match('|^#|', $url)) {
				$replace_url = str_replace($url, "http://$domain/" . $url, $urls[0][$i]);
				$src = str_replace($urls[0][$i], $replace_url, $src);
			}
		}
		if (count($urls[1])) {
			$infos[]= 'Relative URL for links converted to absolute URL';
		}
		
		// Images in CSS
		if (preg_match('|background-image: url\([^\)]+\)|', $content) || preg_match('|list-style-image: url\([^\)]+\)|', $content)) {
			$errors[] = $LANG->getLL('mail_contains_css_images');
		}
		
		// CSS-classes
		if (preg_match('|<[a-z]+ [^>]*class="[^"]+"[^>]*>|', $content)) {
			$warnings[] = $LANG->getLL('mail_contains_css_classes');
		}
		
		// Positioning & element sizes in CSS
		$forbiddenCssProperties = array('width', 'margin', 'height', 'padding', 'position');
		if (preg_match_all('|<[a-z]+[^>]+style="([^"]*)"|', $content, $matches)) {
			foreach ($matches[1] as $stylepart) {
				foreach ($forbiddenCssProperties as $property)
				{
					if (strpos($stylepart, 'width') !== false) {
						$warnings[] = str_replace('###PROPERTY###', $property, $LANG->getLL('mail_contains_css_some_property'));
					}
				}
			}
		}
		
		return array(
			'content' => $content,
			'errors' => $errors,
			'warnings' => $warnings,
			'infos' => $infos,
		);
	}
	
}
?>
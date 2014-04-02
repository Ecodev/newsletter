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
 * ************************************************************* */

/**
 * Newsletter represents a page to be sent to a specific time to several recipients.
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Newsletter_Domain_Model_Newsletter extends Tx_Extbase_DomainObject_AbstractEntity
{

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
     * @lazy
     * @var Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount
     */
    protected $bounceAccount;

    /**
     * UID of the bounce account. Only exist for ease of use with ExtJS
     * @var integer $uidBounceAccount
     */
    protected $uidBounceAccount;

    /**
     * recipientList
     * @lazy
     * @var Tx_Newsletter_Domain_Model_RecipientList $recipientList
     */
    protected $recipientList;

    /**
     * UID of the bounce account. Only exist for ease of use with ExtJS
     * @var integer $uidRecipientList
     */
    protected $uidRecipientList;

    /**
     * @var Tx_Extbase_Object_ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Set default values for new newsletter
        $this->setPlainConverter('Tx_Newsletter_Domain_Model_PlainConverter_Builtin');
        $this->setRepetition(0);
        $this->setPlannedTime(new DateTime());
        $this->setInjectOpenSpy(true);
        $this->setInjectLinksSpy(true);
    }

    /**
     * Returns the ObjectManager
     * @return Tx_Extbase_Object_ObjectManagerInterface
     */
    protected function getObjectManager()
    {
        if (!$this->objectManager)
            $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Tx_Extbase_Object_ObjectManager');

        return $this->objectManager;
    }

    /**
     * Setter for uid
     * @param integer $uid
     * @return void
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * Setter for plannedTime
     *
     * @param DateTime $plannedTime When the newsletter will start sending emails
     * @return void
     */
    public function setPlannedTime(DateTime $plannedTime)
    {
        $this->plannedTime = $plannedTime;
    }

    /**
     * Getter for plannedTime
     *
     * @return DateTime When the newsletter will start sending emails
     */
    public function getPlannedTime()
    {
        return $this->plannedTime;
    }

    /**
     * Setter for beginTime
     *
     * @param DateTime $beginTime beginTime
     * @return void
     */
    public function setBeginTime(DateTime $beginTime)
    {
        $this->beginTime = $beginTime;
    }

    /**
     * Getter for beginTime
     *
     * @return DateTime beginTime
     */
    public function getBeginTime()
    {
        return $this->beginTime;
    }

    /**
     * Setter for endTime
     *
     * @param DateTime $endTime endTime
     * @return void
     */
    public function setEndTime(DateTime $endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * Getter for endTime
     *
     * @return DateTime endTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Setter for repetition
     *
     * @param integer $repetition 0-7 values to indicates when this newsletter will repeat
     * @return void
     */
    public function setRepetition($repetition)
    {
        $this->repetition = $repetition;
    }

    /**
     * Getter for repetition
     *
     * @return integer 0-7 values to indicates when this newsletter will repeat
     */
    public function getRepetition()
    {
        return $this->repetition;
    }

    /**
     * Setter for plainConverter
     *
     * @param string $plainConverter Tool used to convert to plain text
     * @return void
     */
    public function setPlainConverter($plainConverter)
    {
        $this->plainConverter = $plainConverter;
    }

    /**
     * Getter for plainConverter
     *
     * @return string Tool used to convert to plain text
     */
    public function getPlainConverter()
    {
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

        // Fallback to builtin converter
        if (!class_exists($class)) {
            $class = 'Tx_Newsletter_Domain_Model_PlainConverter_Builtin';
        }

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
    public function setIsTest($isTest)
    {
        $this->isTest = $isTest;
    }

    /**
     * Getter for isTest
     *
     * @return boolean Whether this newsletter is for test purpose. If it is it will be ignored in statistics
     */
    public function getIsTest()
    {
        return $this->isTest;
    }

    /**
     * Returns the state of isTest
     *
     * @return boolean the state of isTest
     */
    public function isIsTest()
    {
        return $this->getIsTest();
    }

    /**
     * Setter for attachments
     *
     * @param string $attachments List of files to be attached (comma separated list
     * @return void
     */
    public function setAttachments($attachments)
    {
        $this->attachments = join(',', $attachments);
    }

    /**
     * Getter for attachments
     *
     * @return string List of files to be attached (comma separated list
     */
    public function getAttachments()
    {
        return explode(',', $this->attachments);
    }

    /**
     * Setter for senderName
     *
     * @param string $senderName The name of the newsletter sender
     * @return void
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;
    }

    /**
     * Gets the correct sendername for a newsletter.
     * This is either:
     * The sender name defined on the newsletter record.
     * or the sender name defined in $TYPO3_CONF_VARS['EXTCONF']['newsletter']['senderName']
     * or The sites name as defined in $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename']
     *
     * @global t3lib_DB $TYPO3_DB
     * @return string The name of the newsletter sender
     */
    public function getSenderName()
    {
        global $TYPO3_DB;

        // Return the senderName defined on the newsletter
        if ($this->senderName) {
            return $this->senderName;
        }

        // Return the senderName defined in extension configuration
        $sender = Tx_Newsletter_Tools::confParam('sender_name');
        if ($sender == 'user') {
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
        elseif ($sender) {
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
    public function setSenderEmail($senderEmail)
    {
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
     * @global t3lib_DB $TYPO3_DB
     * @return string The email of the newsletter sender
     */
    public function getSenderEmail()
    {
        global $TYPO3_DB;

        /* The sender defined on the page? */
        if (\TYPO3\CMS\Core\Utility\GeneralUtility::validEmail($this->senderEmail)) {
            return $this->senderEmail;
        }

        /* Anything in typo3_conf_vars? */
        $email = Tx_Newsletter_Tools::confParam('sender_email');
        if ($email == 'user') {
            /* Use the page-owner as user */
            $rs = $TYPO3_DB->sql_query("SELECT email
			FROM be_users bu
			LEFT JOIN pages p ON bu.uid = p.perms_userid
			WHERE p.uid = $this->pid");

            list($email) = $GLOBALS['TYPO3_DB']->sql_fetch_row($rs);
            if (\TYPO3\CMS\Core\Utility\GeneralUtility::validEmail($email)) {
                return $email;
            }
        }

        /* Maybe it was a hardcoded email address? */
        if (\TYPO3\CMS\Core\Utility\GeneralUtility::validEmail($email)) {
            return $email;
        }

        /* If this did not yield an email address, try to use the system-user */
        if (ini_get('safe_mode') || TYPO3_OS == 'WIN') {
            return "no-reply@" . $_SERVER['HTTP_HOST'];
        }

        return trim(exec('whoami')) . '@' . trim(exec('hostname'));
    }

    /**
     * Setter for injectOpenSpy
     *
     * @param boolean $injectOpenSpy injectOpenSpy
     * @return void
     */
    public function setInjectOpenSpy($injectOpenSpy)
    {
        $this->injectOpenSpy = $injectOpenSpy;
    }

    /**
     * Getter for injectOpenSpy
     *
     * @return boolean injectOpenSpy
     */
    public function getInjectOpenSpy()
    {
        return $this->injectOpenSpy;
    }

    /**
     * Returns the state of injectOpenSpy
     *
     * @return boolean the state of injectOpenSpy
     */
    public function isInjectOpenSpy()
    {
        return $this->getInjectOpenSpy();
    }

    /**
     * Setter for injectLinksSpy
     *
     * @param boolean $injectLinksSpy injectLinksSpy
     * @return void
     */
    public function setInjectLinksSpy($injectLinksSpy)
    {
        $this->injectLinksSpy = $injectLinksSpy;
    }

    /**
     * Getter for injectLinksSpy
     *
     * @return boolean injectLinksSpy
     */
    public function getInjectLinksSpy()
    {
        return $this->injectLinksSpy;
    }

    /**
     * Returns the state of injectLinksSpy
     *
     * @return boolean the state of injectLinksSpy
     */
    public function isInjectLinksSpy()
    {
        return $this->getInjectLinksSpy();
    }

    /**
     * Setter for bounceAccount
     *
     * @param Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount bounceAccount
     * @return void
     */
    public function setBounceAccount(Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount = null)
    {
        $this->bounceAccount = $bounceAccount;
    }

    /**
     * Getter for bounceAccount's UID
     *
     * @return integer uidBounceAccount
     */
    public function getUidBounceAccount()
    {
        $bounceAccount = $this->getBounceAccount();
        if ($bounceAccount)
            return $bounceAccount->getUid();
        else
            return null;
    }

    /**
     * Setter for bounceAccount's UID
     *
     * @param integer $uidBounceAccount
     * @return void
     */
    public function setUidBounceAccount($uidBounceAccount = null)
    {
        $bounceAccountRepository = $this->getObjectManager()->get('Tx_Newsletter_Domain_Repository_BounceAccountRepository');
        $bounceAccount = $bounceAccountRepository->findByUid($uidBounceAccount);
        $this->setBounceAccount($bounceAccount);
    }

    /**
     * Getter for bounceAccount
     *
     * @return Tx_Newsletter_Domain_Model_BounceAccount bounceAccount
     */
    public function getBounceAccount()
    {
        return $this->bounceAccount;
    }

    /**
     * Setter for recipientList
     *
     * @param Tx_Newsletter_Domain_Model_RecipientList $recipientList recipientList
     * @return void
     */
    public function setRecipientList(Tx_Newsletter_Domain_Model_RecipientList $recipientList)
    {
        $this->recipientList = $recipientList;
    }

    /**
     * Getter for recipientList
     *
     * @return Tx_Newsletter_Domain_Model_RecipientList recipientList
     */
    public function getRecipientList()
    {
        return $this->recipientList;
    }

    /**
     * Getter for recipientList's UID
     *
     * @return integer uidRecipientList
     */
    public function getUidRecipientList()
    {
        $recipientList = $this->getRecipientList();
        if ($recipientList)
            return $recipientList->getUid();
        else
            return null;
    }

    /**
     * Setter for recipientList's UID
     *
     * @param integer $uidRecipientList
     * @return void
     */
    public function setUidRecipientList($uidRecipientList)
    {
        $recipientListRepository = $this->getObjectManager()->get('Tx_Newsletter_Domain_Repository_RecipientListRepository');
        $recipientList = $recipientListRepository->findByUid($uidRecipientList);
        $this->setRecipientList($recipientList);
    }

    /**
     * Function to fetch the proper domain from which to fetch content for newsletter.
     * This is either a sys_domain record from the page tree or the fetch_path property.
     *
     * @global t3lib_DB $TYPO3_DB
     * @return string Correct domain.
     */
    public function getDomain()
    {
        global $TYPO3_DB;

        // Is anything hardcoded from TYPO3_CONF_VARS ?
        $domain = Tx_Newsletter_Tools::confParam('fetch_path');

        // Else we try to resolve a domain in page root line
        if (!$domain) {
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
        }

        // Else we try to find it in sys_template (available at least since TYPO3 4.6 Introduction Package)
        if (!$domain) {
            $rootLine = t3lib_befunc::BEgetRootLine($this->pid);
            $parser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('t3lib_tsparser_ext'); // Defined global here!
            $parser->tt_track = 0; // Do not log time-performance information
            $parser->init();
            $parser->runThroughTemplates($rootLine); // This generates the constants/config + hierarchy info for the template.
            $parser->generateConfig();
            if (isset($parser->flatSetup['config.domain'])) {
                $domain = $parser->flatSetup['config.domain'];
            }
        }

        // If still no domain, can't continue
        if (!$domain) {
            throw new Exception("Could not find the domain name. Use Newsletter configuration page to set 'fetch_path'");
        }

        return $domain;
    }

    /**
     * Returns the title, NOT localized, of the page sent by this newsletter.
     * This should only used for BE, because newsletter recipients need localized title
     * @global t3lib_DB $TYPO3_DB
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
     * @global t3lib_DB $TYPO3_DB
     */
    public function scheduleNextNewsletter()
    {
        $plannedTime = $this->getPlannedTime();
        list($year, $month, $day, $hour, $minute) = explode('-', date("Y-n-j-G-i", $plannedTime->format('U')));

        switch ($this->getRepetition()) {
            case 0: return;
            case 1: $day += 1;
                break;
            case 2: $day += 7;
                break;
            case 3: $day += 14;
                break;
            case 4: $month += 1;
                break;
            case 5: $month += 3;
                break;
            case 6: $month += 6;
                break;
            case 7: $year += 1;
                break;
        }
        $newPlannedTime = mktime($hour, $minute, 0, $month, $day, $year);


        // Clone this newsletter and give the new plannedTime
        // We cannot use extbase because __clone() doesn't work and even if we clone manually the PID cannot be set
        global $TYPO3_DB;
        $TYPO3_DB->sql_query("INSERT tx_newsletter_domain_model_newsletter
		SELECT null AS uid, pid, '$newPlannedTime' AS planned_time, 0 AS begin_time, 0 AS end_time, repetition, plain_converter, is_test, attachments, sender_name, sender_email, inject_open_spy, inject_links_spy, bounce_account, recipient_list, " . time() . " AS tstamp, " . time() . " AS crdate, deleted, hidden
		FROM tx_newsletter_domain_model_newsletter WHERE uid = " . $this->getUid());
    }

    /**
     * Returns the count of recipient to which the newsletter was actually sent (or going to be sent if the process is not finished yet).
     * This may differ from $newsletter->getRecipientList()->getCount()
     * because the recipientList may change over time.
     */
    public function getEmailCount()
    {
        // If the newsletter didn't start, we rely on recipientList to tell us how many email there will be
        if (!$this->getBeginTime()) {
            $recipientList = $this->getRecipientList();
            $recipientList->init();
            return $recipientList->getCount();
        }

        $emailRepository = $this->getObjectManager()->get('Tx_Newsletter_Domain_Repository_EmailRepository');
        return $emailRepository->getCount($this->uid);
    }

    /**
     * Get the number of not yet sent email
     * @global t3lib_DB $TYPO3_DB
     */
    public function getEmailNotSentCount()
    {
        global $TYPO3_DB;

        // If the newsletter didn't start, then it means all emails are "not sent"
        if (!$this->getBeginTime()) {
            return $this->getEmailCount();
        }

        $numberOfNotSent = $TYPO3_DB->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'end_time = 0 AND newsletter = ' . $this->getUid());

        return (int) $numberOfNotSent;
    }

    /**
     * Returns the URL of the content of this newsletter
     * @return string
     */
    public function getContentUrl($language = null)
    {
        $append_url = Tx_Newsletter_Tools::confParam('append_url');
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
     * @return array ('content' => $content, 'errors' => $errors, 'warnings' => $warnings, 'infos' => $infos);
     */
    public function getValidatedContent($language = null)
    {
        // Here we need to include the locallization file for ExtDirect calls, otherwise we get empty strings
        global $LANG;
        if (is_null($LANG)) {
            $LANG = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('language'); // create language-object
            $LLkey = 'default';
            if ($GLOBALS['TSFE']->config['config']['language']) {
                $LLkey = $GLOBALS['TSFE']->config['config']['language'];
            }
            $LANG->init($LLkey); // initalize language-object with actual language
        }
        $LANG->includeLLFile('EXT:newsletter/Resources/Private/Language/locallang.xml');

        // We need to catch the exception if domain was not found/configured properly
        try {
            $url = $this->getContentUrl($language);
        } catch (Exception $e) {

            return array(
                'content' => '',
                'errors' => array($e->getMessage()),
                'warnings' => array(),
                'infos' => array(),
            );
        }

        $content = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($url);

        $errors = array();
        $warnings = array();
        $infos = array(sprintf($LANG->getLL('validation_content_url'), $url));

        // Content should be more that just a few characters. Apache error propably occured
        if (strlen($content) < 200) {
            $errors [] = $LANG->getLL('validation_mail_too_short');
        }

        // Content should not contain PHP-Warnings
        if (substr($content, 0, 22) == "<br />\n<b>Warning</b>:") {
            $errors [] = $LANG->getLL('validation_mail_contains_php_warnings');
        }

        // Content should not contain PHP-Warnings
        if (substr($content, 0, 26) == "<br />\n<b>Fatal error</b>:") {
            $errors [] = $LANG->getLL('validation_mail_contains_php_errors');
        }

        // If the page contains a "Pages is being generared" text... this is bad too
        if (strpos($content, 'Page is being generated.') && strpos($content, 'If this message does not disappear within')) {
            $errors [] = $LANG->getLL('validation_mail_being_generated');
        }


        // Find out the absolute domain. If specified in HTML source, use it as is.
        if (preg_match('|<base[^>]*href="([^"]*)"[^>]*/>|i', $content, $match)) {
            $absoluteDomain = $match[1];
        }
        // Otherwise try our best to guess what it is
        else {
            $absoluteDomain = 'http://' . $this->getDomain() . '/';
        }

        // Fix relative URL to absolute URL
        $urlPatterns = array(
            'hyperlinks' => '/<a [^>]*href="(.*)"/Ui',
            'stylesheets' => '/<link [^>]*href="(.*)"/Ui',
            'images' => '/ src="(.*)"/Ui',
            'background images' => '/ background="(.*)"/Ui',
        );
        foreach ($urlPatterns as $type => $urlPattern) {
            preg_match_all($urlPattern, $content, $urls);
            foreach ($urls[1] as $i => $url) {
                // If this is already an absolute link, dont replace it
                if (!preg_match('-^(http://|https://|ftp://|mailto:|#)-i', $url)) {
                    $replace_url = str_replace($url, $absoluteDomain . $url, $urls[0][$i]);
                    $content = str_replace($urls[0][$i], $replace_url, $content);
                }
            }

            if (count($urls[1])) {
                $infos[] = sprintf($LANG->getLL('validation_mail_converted_relative_url'), $type);
            }
        }

        // Find linked css and convert into a style-tag
        preg_match_all('|<link rel="stylesheet" type="text/css" href="([^"]+)"[^>]+>|Ui', $content, $urls);
        foreach ($urls[1] as $i => $url) {

            $content = str_replace($urls[0][$i], "<!-- fetched URL: $url -->
<style type=\"text/css\">\n<!--\n" . \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($url) . "\n-->\n</style>", $content);
        }
        if (count($urls[1])) {
            $infos[] = $LANG->getLL('validation_mail_contains_linked_styles');
        }

        // We cant very well have attached javascript in a newsmail ... removing
        $content = preg_replace('|<script[^>]*type="text/javascript"[^>]*>[^<]*</script>|i', '', $content, -1, $count);
        if ($count) {
            $warnings[] = $LANG->getLL('validation_mail_contains_javascript');
        }

        // Images in CSS
        if (preg_match('|background-image: url\([^\)]+\)|', $content) || preg_match('|list-style-image: url\([^\)]+\)|', $content)) {
            $errors[] = $LANG->getLL('validation_mail_contains_css_images');
        }

        // CSS-classes
        if (preg_match('|<[a-z]+ [^>]*class="[^"]+"[^>]*>|', $content)) {
            $warnings[] = $LANG->getLL('validation_mail_contains_css_classes');
        }

        // Positioning & element sizes in CSS
        $forbiddenCssProperties = array('width', 'margin', 'height', 'padding', 'position');
        if (preg_match_all('|<[a-z]+[^>]+style="([^"]*)"|', $content, $matches)) {
            foreach ($matches[1] as $stylepart) {
                foreach ($forbiddenCssProperties as $property) {
                    if (strpos($stylepart, 'width') !== false) {
                        $warnings[] = sprintf($LANG->getLL('validation_mail_contains_css_some_property'), $property);
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

    /**
     * Return a human readable status for the newsletter
     * @return string
     */
    public function getStatus()
    {
        // Here we need to include the locallization file for ExtDirect calls, otherwise we get empty strings
        global $LANG;
        $LANG->includeLLFile('EXT:newsletter/Resources/Private/Language/locallang.xml');

        $plannedTime = $this->getPlannedTime();
        $beginTime = $this->getBeginTime();
        $endTime = $this->getEndTime();

        // If we don't have a valid UID, it means we are a "fake model" newsletter not saved yet
        if (!($this->getUid() > 0))
            return $LANG->getLL('newsletter_status_not_planned');

        if ($plannedTime && !$beginTime)
            return sprintf($LANG->getLL('newsletter_status_planned'), $plannedTime->format(DateTime::ISO8601));

        if ($beginTime && !$endTime)
            return $LANG->getLL('newsletter_status_generating_emails');

        if ($beginTime && $endTime) {
            $emailCount = $this->getEmailCount();
            $emailNotSentCount = $this->getEmailNotSentCount();

            if ($emailNotSentCount)
                return sprintf($LANG->getLL('newsletter_status_sending'), $emailCount - $emailNotSentCount, $emailCount);
            else
                return sprintf($LANG->getLL('newsletter_status_was_sent'), $endTime->format(DateTime::ISO8601));
        }

        return "unexpected status";
    }

    public function getStatistics()
    {
        $newsletterRepository = $this->getObjectManager()->get('Tx_Newsletter_Domain_Repository_NewsletterRepository');
        $stats = $newsletterRepository->getStatistics($this);

        return $stats;
    }

}

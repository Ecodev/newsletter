<?php

namespace Ecodev\Newsletter\Domain\Model;

use DateTime;
use Ecodev\Newsletter\Tools;
use Exception;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015
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
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Newsletter extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
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
    protected $repetition = 0;

    /**
     * Tool used to convert to plain text
     *
     * @var string $plainConverter
     */
    protected $plainConverter = 'Ecodev\\Newsletter\\Domain\\Model\\PlainConverter\\Builtin';

    /**
     * Whether this newsletter is for test purpose. If it is it will be ignored in statistics
     *
     * @var boolean $isTest
     * @validate NotEmpty
     */
    protected $isTest = false;

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
    protected $injectOpenSpy = true;

    /**
     * injectLinksSpy
     *
     * @var boolean $injectLinksSpy
     */
    protected $injectLinksSpy = true;

    /**
     * bounceAccount
     * @lazy
     * @var \Ecodev\Newsletter\Domain\Model\BounceAccount $bounceAccount
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
     * @var \Ecodev\Newsletter\Domain\Model\RecipientList $recipientList
     */
    protected $recipientList;

    /**
     * UID of the bounce account. Only exist for ease of use with ExtJS
     * @var integer $uidRecipientList
     */
    protected $uidRecipientList;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Ecodev\Newsletter\Utility\Validator
     */
    protected $validator;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Set default values for new newsletter
        $this->setPlannedTime(new DateTime());
    }

    /**
     * Returns the ObjectManager
     * @return \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected function getObjectManager()
    {
        if (!$this->objectManager) {
            $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        }

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
     * @return \Ecodev\Newsletter\Domain\Model\IPlainConverter
     */
    public function getPlainConverterInstance()
    {
        $class = $this->getPlainConverter();

        // Instantiate converter or fallback to builtin
        if (class_exists($class)) {
            $converter = new $class();
        } else {
            $converter = new PlainConverter\Builtin();
        }

        if (!($converter instanceof IPlainConverter)) {
            throw new Exception("$class does not implement \Ecodev\Newsletter\Domain\Model\IPlainConverter");
        }

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
        $this->attachments = implode(',', $attachments);
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
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
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
        $sender = Tools::confParam('sender_name');
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
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
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
        $email = Tools::confParam('sender_email');
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
     * @param \Ecodev\Newsletter\Domain\Model\BounceAccount $bounceAccount bounceAccount
     * @return void
     */
    public function setBounceAccount(BounceAccount $bounceAccount = null)
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
        if ($bounceAccount) {
            return $bounceAccount->getUid();
        } else {
            return null;
        }
    }

    /**
     * Setter for bounceAccount's UID
     *
     * @param integer $uidBounceAccount
     * @return void
     */
    public function setUidBounceAccount($uidBounceAccount = null)
    {
        $bounceAccountRepository = $this->getObjectManager()->get('Ecodev\\Newsletter\\Domain\\Repository\\BounceAccountRepository');
        $bounceAccount = $bounceAccountRepository->findByUid($uidBounceAccount);
        $this->setBounceAccount($bounceAccount);
    }

    /**
     * Getter for bounceAccount
     *
     * @return \Ecodev\Newsletter\Domain\Model\BounceAccount bounceAccount
     */
    public function getBounceAccount()
    {
        return $this->bounceAccount;
    }

    /**
     * Setter for recipientList
     *
     * @param \Ecodev\Newsletter\Domain\Model\RecipientList $recipientList recipientList
     * @return void
     */
    public function setRecipientList(RecipientList $recipientList)
    {
        $this->recipientList = $recipientList;
    }

    /**
     * Getter for recipientList
     *
     * @return \Ecodev\Newsletter\Domain\Model\RecipientList recipientList
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
        if ($recipientList) {
            return $recipientList->getUid();
        } else {
            return null;
        }
    }

    /**
     * Setter for recipientList's UID
     *
     * @param integer $uidRecipientList
     * @return void
     */
    public function setUidRecipientList($uidRecipientList)
    {
        $recipientListRepository = $this->getObjectManager()->get('Ecodev\\Newsletter\\Domain\\Repository\\RecipientListRepository');
        $recipientList = $recipientListRepository->findByUid($uidRecipientList);
        $this->setRecipientList($recipientList);
    }

    /**
     * Function to fetch the proper domain from which to fetch content for newsletter.
     * This is either a sys_domain record from the page tree or the fetch_path property.
     *
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @return string Correct domain.
     */
    public function getDomain()
    {
        global $TYPO3_DB;

        // Is anything hardcoded from TYPO3_CONF_VARS ?
        $domain = Tools::confParam('fetch_path');

        // Else we try to resolve a domain in page root line
        if (!$domain) {
            $pids = array_reverse(\TYPO3\CMS\Backend\Utility\BackendUtility::BEgetRootLine($this->pid));
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
            $rootLine = \TYPO3\CMS\Backend\Utility\BackendUtility::BEgetRootLine($this->pid);
            $parser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TypoScript\\ExtendedTemplateService'); // Defined global here!
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
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @return string the title
     */
    public function getTitle()
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
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
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
        $TYPO3_DB->sql_query("INSERT INTO tx_newsletter_domain_model_newsletter
        (uid, pid, planned_time, begin_time, end_time, repetition, plain_converter, is_test, attachments, sender_name, sender_email, inject_open_spy, inject_links_spy, bounce_account, recipient_list, tstamp, crdate, deleted, hidden)
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

        $emailRepository = $this->getObjectManager()->get('Ecodev\\Newsletter\\Domain\\Repository\\EmailRepository');

        return $emailRepository->getCount($this->uid);
    }

    /**
     * Get the number of not yet sent email
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
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
        $append_url = Tools::confParam('append_url');
        $domain = $this->getDomain();

        if (!is_null($language)) {
            $language = '&L=' . $language;
        }
        $protocol = Tools::confParam('protocol'); //stefano: protocol is now set through "basic.protocol" parameter
        return "$protocol$domain/index.php?id=" . $this->getPid() . $language . $append_url;
    }

    /**
     * Set the validator
     * @param \Ecodev\Newsletter\Utility\Validator $validor
     */
    public function setValidator(\Ecodev\Newsletter\Utility\Validator $validor)
    {
        $this->validator = $validor;
    }

    /**
     * Get the validator
     * @return \Ecodev\Newsletter\Utility\Validator
     */
    public function getValidator()
    {
        if (!$this->validator) {
            $this->validator = new \Ecodev\Newsletter\Utility\Validator();
        }

        return $this->validator;
    }

    /**
     * Returns the content of this newsletter with validation messages. The content
     * is also "fixed" automatically when possible.
     * @param string $language language of the content of the newsletter (the 'L' parameter in TYPO3 URL)
     * @return array ('content' => $content, 'errors' => $errors, 'warnings' => $warnings, 'infos' => $infos);
     */
    public function getValidatedContent($language = null)
    {
        return $this->getValidator()->validate($this, $language);
    }

    /**
     * Return a human readable status for the newsletter
     * @return string
     */
    public function getStatus()
    {
        // Here we need to include the locallization file for ExtDirect calls, otherwise we get empty strings
        global $LANG;
        $LANG->includeLLFile('EXT:newsletter/Resources/Private/Language/locallang.xlf');

        $plannedTime = $this->getPlannedTime();
        $beginTime = $this->getBeginTime();
        $endTime = $this->getEndTime();

        // If we don't have a valid UID, it means we are a "fake model" newsletter not saved yet
        if (!($this->getUid() > 0)) {
            return $LANG->getLL('newsletter_status_not_planned');
        }

        if ($plannedTime && !$beginTime) {
            return sprintf($LANG->getLL('newsletter_status_planned'), $plannedTime->format(DateTime::ISO8601));
        }

        if ($beginTime && !$endTime) {
            return $LANG->getLL('newsletter_status_generating_emails');
        }

        if ($beginTime && $endTime) {
            $emailCount = $this->getEmailCount();
            $emailNotSentCount = $this->getEmailNotSentCount();

            if ($emailNotSentCount) {
                return sprintf($LANG->getLL('newsletter_status_sending'), $emailCount - $emailNotSentCount, $emailCount);
            } else {
                return sprintf($LANG->getLL('newsletter_status_was_sent'), $endTime->format(DateTime::ISO8601));
            }
        }

        return "unexpected status";
    }

    public function getStatistics()
    {
        $newsletterRepository = $this->getObjectManager()->get('Ecodev\\Newsletter\\Domain\\Repository\\NewsletterRepository');
        $stats = $newsletterRepository->getStatistics($this);

        return $stats;
    }
}

<?php

namespace Ecodev\Newsletter\Domain\Model;

use DateTime;
use Ecodev\Newsletter\Domain\Model\PlainConverter\Builtin;
use Ecodev\Newsletter\Domain\Repository\BounceAccountRepository;
use Ecodev\Newsletter\Domain\Repository\EmailRepository;
use Ecodev\Newsletter\Domain\Repository\NewsletterRepository;
use Ecodev\Newsletter\Domain\Repository\RecipientListRepository;
use Ecodev\Newsletter\Tools;
use Ecodev\Newsletter\Utility\Validator;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\TypoScript\ExtendedTemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Newsletter represents a page to be sent to a specific time to several recipients.
 */
class Newsletter extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * When the newsletter will start sending emails
     *
     * @var DateTime
     * @validate NotEmpty
     */
    protected $plannedTime;

    /**
     * beginTime
     *
     * @var DateTime
     */
    protected $beginTime;

    /**
     * endTime
     *
     * @var DateTime
     */
    protected $endTime;

    /**
     * 0-7 values to indicates when this newsletter will repeat
     *
     * @var int
     */
    protected $repetition = 0;

    /**
     * Tool used to convert to plain text
     *
     * @var string
     */
    protected $plainConverter = Builtin::class;

    /**
     * Whether this newsletter is for test purpose. If it is it will be ignored in statistics
     *
     * @var bool
     * @validate NotEmpty
     */
    protected $isTest = false;

    /**
     * List of files to be attached (comma separated list)
     *
     * @var string
     */
    protected $attachments;

    /**
     * The name of the newsletter sender
     *
     * @var string
     * @validate NotEmpty
     */
    protected $senderName;

    /**
     * The email of the newsletter sender
     *
     * @var string
     * @validate NotEmpty
     */
    protected $senderEmail;

    /**
     * The Reply-To name of the newsletter
     *
     * @var string
     */
    protected $replytoName;

    /**
     * The Reply-To <email> of the newsletter
     *
     * @var string
     */
    protected $replytoEmail;

    /**
     * injectOpenSpy
     *
     * @var bool
     */
    protected $injectOpenSpy = true;

    /**
     * injectLinksSpy
     *
     * @var bool
     */
    protected $injectLinksSpy = true;

    /**
     * bounceAccount
     *
     * @lazy
     * @var BounceAccount
     */
    protected $bounceAccount;

    /**
     * UID of the bounce account. Only exist for ease of use with ExtJS
     *
     * @var int
     */
    protected $uidBounceAccount;

    /**
     * recipientList
     *
     * @lazy
     * @var RecipientList
     */
    protected $recipientList;

    /**
     * UID of the bounce account. Only exist for ease of use with ExtJS
     *
     * @var int
     */
    protected $uidRecipientList;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var Validator
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
     *
     * @return \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected function getObjectManager()
    {
        if (!$this->objectManager) {
            $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        }

        return $this->objectManager;
    }

    /**
     * Setter for uid
     *
     * @param int $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * Setter for plannedTime
     *
     * @param DateTime $plannedTime When the newsletter will start sending emails
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
     * @param int $repetition 0-7 values to indicates when this newsletter will repeat
     */
    public function setRepetition($repetition)
    {
        $this->repetition = $repetition;
    }

    /**
     * Getter for repetition
     *
     * @return int 0-7 values to indicates when this newsletter will repeat
     */
    public function getRepetition()
    {
        return $this->repetition;
    }

    /**
     * Setter for plainConverter
     *
     * @param string $plainConverter Tool used to convert to plain text
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
     *
     * @throws \Exception
     * @return IPlainConverter
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
            throw new \Exception("$class does not implement \Ecodev\Newsletter\Domain\Model\IPlainConverter");
        }

        return $converter;
    }

    /**
     * Setter for isTest
     *
     * @param bool $isTest Whether this newsletter is for test purpose. If it is it will be ignored in statistics
     */
    public function setIsTest($isTest)
    {
        $this->isTest = $isTest;
    }

    /**
     * Getter for isTest
     *
     * @return bool Whether this newsletter is for test purpose. If it is it will be ignored in statistics
     */
    public function getIsTest()
    {
        return $this->isTest;
    }

    /**
     * Returns the state of isTest
     *
     * @return bool the state of isTest
     */
    public function isIsTest()
    {
        return $this->getIsTest();
    }

    /**
     * Setter for attachments
     *
     * @param string $attachments List of files to be attached (comma separated list
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
     * @return string The name of the newsletter sender
     */
    public function getSenderName()
    {
        $db = Tools::getDatabaseConnection();

        // Return the senderName defined on the newsletter
        if ($this->senderName) {
            return $this->senderName;
        }

        // Return the senderName defined in extension configuration
        $sender = Tools::confParam('sender_name');
        if ($sender == 'user') {
            // Use the page-owner as user
            $rs = $db->sql_query("SELECT realName
							  FROM be_users
							  LEFT JOIN pages ON be_users.uid = pages.perms_userid
							  WHERE pages.uid = $this->pid");

            list($sender) = $db->sql_fetch_row($rs);
            if ($sender) {
                return $sender;
            }
        } // Returns the name as defined in configuration
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
     * @return string The email of the newsletter sender
     */
    public function getSenderEmail()
    {
        $db = Tools::getDatabaseConnection();

        /* The sender defined on the page? */
        if (GeneralUtility::validEmail($this->senderEmail)) {
            return $this->senderEmail;
        }

        /* Anything in typo3_conf_vars? */
        $email = Tools::confParam('sender_email');
        if ($email == 'user') {
            /* Use the page-owner as user */
            $rs = $db->sql_query("SELECT email
			FROM be_users bu
			LEFT JOIN pages p ON bu.uid = p.perms_userid
			WHERE p.uid = $this->pid");

            list($email) = Tools::getDatabaseConnection()->sql_fetch_row($rs);
            if (GeneralUtility::validEmail($email)) {
                return $email;
            }
        }

        /* Maybe it was a hardcoded email address? */
        if (GeneralUtility::validEmail($email)) {
            return $email;
        }

        /* If this did not yield an email address, try to use the system-user */
        if (ini_get('safe_mode') || TYPO3_OS == 'WIN') {
            return 'no-reply@' . $_SERVER['HTTP_HOST'];
        }

        return trim(exec('whoami')) . '@' . trim(exec('hostname'));
    }

    /**
     * Setter for Reply-To: name
     *
     * @param string $replytoName
     */
    public function setReplytoName($replytoName)
    {
        $this->replytoName = $replytoName;
    }

    /**
     * Getter for Reply-To: name
     *
     * @return string
     */
    public function getReplytoName()
    {
        // Return the replytoName defined on the newsletter
        if ($this->replytoName) {
            return $this->replytoName;
        }

        // Return the replytoName defined in extension configuration
        $replytoName = Tools::confParam('replyto_name');
        if ($replytoName) {
            return $replytoName;
        }

        // Return empty
        return '';
    }

    /**
     * Setter for Reply-To: <email>
     *
     * @param string $replytoEmail
     */
    public function setReplytoEmail($replytoEmail)
    {
        $this->replytoEmail = $replytoEmail;
    }

    /**
     * Getter for Reply-To: <email>
     *
     * @return string
     */
    public function getReplytoEmail()
    {
        // Return the replytoEmail defined on the newsletter
        if (GeneralUtility::validEmail($this->replytoEmail)) {
            return $this->replytoEmail;
        }

        // Return the replytoEmail defined in extension configuration
        $replytoEmail = Tools::confParam('replyto_email');
        if (GeneralUtility::validEmail($replytoEmail)) {
            return $replytoEmail;
        }

        // Return empty
        return '';
    }

    /**
     * Setter for injectOpenSpy
     *
     * @param bool $injectOpenSpy injectOpenSpy
     */
    public function setInjectOpenSpy($injectOpenSpy)
    {
        $this->injectOpenSpy = $injectOpenSpy;
    }

    /**
     * Getter for injectOpenSpy
     *
     * @return bool injectOpenSpy
     */
    public function getInjectOpenSpy()
    {
        return $this->injectOpenSpy;
    }

    /**
     * Returns the state of injectOpenSpy
     *
     * @return bool the state of injectOpenSpy
     */
    public function isInjectOpenSpy()
    {
        return $this->getInjectOpenSpy();
    }

    /**
     * Setter for injectLinksSpy
     *
     * @param bool $injectLinksSpy injectLinksSpy
     */
    public function setInjectLinksSpy($injectLinksSpy)
    {
        $this->injectLinksSpy = $injectLinksSpy;
    }

    /**
     * Getter for injectLinksSpy
     *
     * @return bool injectLinksSpy
     */
    public function getInjectLinksSpy()
    {
        return $this->injectLinksSpy;
    }

    /**
     * Returns the state of injectLinksSpy
     *
     * @return bool the state of injectLinksSpy
     */
    public function isInjectLinksSpy()
    {
        return $this->getInjectLinksSpy();
    }

    /**
     * Setter for bounceAccount
     *
     * @param BounceAccount $bounceAccount bounceAccount
     */
    public function setBounceAccount(BounceAccount $bounceAccount = null)
    {
        $this->bounceAccount = $bounceAccount;
    }

    /**
     * Getter for bounceAccount's UID
     *
     * @return int uidBounceAccount
     */
    public function getUidBounceAccount()
    {
        $bounceAccount = $this->getBounceAccount();
        if ($bounceAccount) {
            return $bounceAccount->getUid();
        }
    }

    /**
     * Setter for bounceAccount's UID
     *
     * @param int $uidBounceAccount
     */
    public function setUidBounceAccount($uidBounceAccount = null)
    {
        $bounceAccountRepository = $this->getObjectManager()->get(BounceAccountRepository::class);
        $bounceAccount = $bounceAccountRepository->findByUid($uidBounceAccount);
        $this->setBounceAccount($bounceAccount);
    }

    /**
     * Getter for bounceAccount
     *
     * @return BounceAccount bounceAccount
     */
    public function getBounceAccount()
    {
        return $this->bounceAccount;
    }

    /**
     * Setter for recipientList
     *
     * @param RecipientList $recipientList recipientList
     */
    public function setRecipientList(RecipientList $recipientList)
    {
        $this->recipientList = $recipientList;
    }

    /**
     * Getter for recipientList
     *
     * @return RecipientList recipientList
     */
    public function getRecipientList()
    {
        return $this->recipientList;
    }

    /**
     * Getter for recipientList's UID
     *
     * @return int uidRecipientList
     */
    public function getUidRecipientList()
    {
        $recipientList = $this->getRecipientList();
        if ($recipientList) {
            return $recipientList->getUid();
        }
    }

    /**
     * Setter for recipientList's UID
     *
     * @param int $uidRecipientList
     */
    public function setUidRecipientList($uidRecipientList)
    {
        $recipientListRepository = $this->getObjectManager()->get(RecipientListRepository::class);
        $recipientList = $recipientListRepository->findByUid($uidRecipientList);
        $this->setRecipientList($recipientList);
    }

    /**
     * Returns the proper base URL (scheme + domain + path) from which to fetch content for newsletter.
     * This is either a sys_domain record from the page tree or the fetch_path property.
     *
     * @return string Base URL, eg: https://www.example.com/path
     */
    public function getBaseUrl()
    {
        $db = Tools::getDatabaseConnection();

        // Is anything hardcoded from TYPO3_CONF_VARS ?
        $domain = Tools::confParam('fetch_path');

        // Else we try to resolve a domain in page root line
        if (!$domain) {
            $pids = array_reverse(BackendUtility::BEgetRootLine($this->pid));
            foreach ($pids as $page) {
                /* Domains */
                $rs = $db->sql_query("SELECT domainName FROM sys_domain
								INNER JOIN pages ON sys_domain.pid = pages.uid
								WHERE NOT sys_domain.hidden
								AND NOT pages.hidden
								AND NOT pages.deleted
								AND pages.uid = $page[uid]
								ORDER BY sys_domain.sorting
								LIMIT 0,1");

                if ($db->sql_num_rows($rs)) {
                    list($domain) = $db->sql_fetch_row($rs);
                }
            }
        }

        // Else we try to find it in sys_template (available at least since TYPO3 4.6 Introduction Package)
        if (!$domain) {
            $rootLine = BackendUtility::BEgetRootLine($this->pid);
            $parser = GeneralUtility::makeInstance(ExtendedTemplateService::class); // Defined global here!
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
            throw new \Exception("Could not find the domain name. Use Newsletter configuration page to set 'fetch_path'");
        }

        // Force scheme if found from domain record, or if fetch_path was not configured properly (before Newsletter 2.6.0)
        if (!preg_match('~^https?://~', $domain)) {
            $domain = 'http://' . $domain;
        }

        return $domain;
    }

    /**
     * Get domain name
     *
     * @return string domain, eg: www.example.com
     */
    public function getDomain()
    {
        return parse_url($this->getBaseUrl(), PHP_URL_HOST);
    }

    /**
     * Returns the title, NOT localized, of the page sent by this newsletter.
     * This should only used for BE, because newsletter recipients need localized title
     *
     * @return string the title
     */
    public function getTitle()
    {
        $db = Tools::getDatabaseConnection();
        $rs = $db->sql_query("SELECT title FROM pages WHERE uid = $this->pid");

        $title = '';
        if ($db->sql_num_rows($rs)) {
            list($title) = $db->sql_fetch_row($rs);
        }

        return $title;
    }

    /**
     * Schedule the next newsletter if it defined to be repeated
     */
    public function scheduleNextNewsletter()
    {
        $plannedTime = $this->getPlannedTime();
        list($year, $month, $day, $hour, $minute) = explode('-', date('Y-n-j-G-i', $plannedTime->format('U')));

        switch ($this->getRepetition()) {
            case 0:
                return;
            case 1:
                $day += 1;
                break;
            case 2:
                $day += 7;
                break;
            case 3:
                $day += 14;
                break;
            case 4:
                $month += 1;
                break;
            case 5:
                $month += 3;
                break;
            case 6:
                $month += 6;
                break;
            case 7:
                $year += 1;
                break;
        }
        $newPlannedTime = mktime($hour, $minute, 0, $month, $day, $year);

        // Clone this newsletter and give the new plannedTime
        // We cannot use extbase because __clone() doesn't work and even if we clone manually the PID cannot be set
        $db = Tools::getDatabaseConnection();
        $db->sql_query("INSERT INTO tx_newsletter_domain_model_newsletter
        (uid, pid, planned_time, begin_time, end_time, repetition, plain_converter, is_test, attachments, sender_name, sender_email, replyto_name, replyto_email, inject_open_spy, inject_links_spy, bounce_account, recipient_list, tstamp, crdate, deleted, hidden)
		SELECT null AS uid, pid, '$newPlannedTime' AS planned_time, 0 AS begin_time, 0 AS end_time, repetition, plain_converter, is_test, attachments, sender_name, sender_email, replyto_name, replyto_email, inject_open_spy, inject_links_spy, bounce_account, recipient_list, " . time() . ' AS tstamp, ' . time() . ' AS crdate, deleted, hidden
		FROM tx_newsletter_domain_model_newsletter WHERE uid = ' . $this->getUid());
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

        $emailRepository = $this->getObjectManager()->get(EmailRepository::class);

        return $emailRepository->getCount($this->uid);
    }

    /**
     * Get the number of not yet sent email
     */
    public function getEmailNotSentCount()
    {
        $db = Tools::getDatabaseConnection();

        // If the newsletter didn't start, then it means all emails are "not sent"
        if (!$this->getBeginTime()) {
            return $this->getEmailCount();
        }

        $numberOfNotSent = $db->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'end_time = 0 AND newsletter = ' . $this->getUid());

        return (int) $numberOfNotSent;
    }

    /**
     * Returns the URL of the content of this newsletter
     *
     * @param int $language
     *
     * @return string
     */
    public function getContentUrl($language = null)
    {
        $append_url = Tools::confParam('append_url');
        $baseUrl = $this->getBaseUrl();

        if (!is_null($language)) {
            $language = '&L=' . $language;
        }

        return $baseUrl . '/index.php?id=' . $this->getPid() . $language . $append_url;
    }

    /**
     * Set the validator
     *
     * @param Validator $validor
     */
    public function setValidator(Validator $validor)
    {
        $this->validator = $validor;
    }

    /**
     * Get the validator
     *
     * @return Validator
     */
    public function getValidator()
    {
        if (!$this->validator) {
            $this->validator = new Validator();
        }

        return $this->validator;
    }

    /**
     * Returns the content of this newsletter with validation messages. The content
     * is also "fixed" automatically when possible.
     *
     * @param string $language language of the content of the newsletter (the 'L' parameter in TYPO3 URL)
     *
     * @return array ('content' => $content, 'errors' => $errors, 'warnings' => $warnings, 'infos' => $infos);
     */
    public function getValidatedContent($language = null)
    {
        return $this->getValidator()->validate($this, $language);
    }

    /**
     * Return a human readable status for the newsletter
     *
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
            }

            return sprintf($LANG->getLL('newsletter_status_was_sent'), $endTime->format(DateTime::ISO8601));
        }

        return 'unexpected status';
    }

    /**
     * Returns newsletter statistics to be used for pie and timeline chart
     *
     * @return array eg: array(array(time, emailNotSentCount, emailSentCount, emailOpenedCount, emailBouncedCount, emailCount, linkOpenedCount, linkCount, [and same fields but Percentage instead of Count] ))
     */
    public function getStatistics()
    {
        $newsletterRepository = $this->getObjectManager()->get(NewsletterRepository::class);
        $stats = $newsletterRepository->getStatistics($this);

        return $stats;
    }
}

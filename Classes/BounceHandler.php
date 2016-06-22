<?php

namespace Ecodev\Newsletter;

use DateTime;
use Exception;

/**
 * Handle bounced emails. Fetch them, analyse them and take approriate actions.
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class BounceHandler
{
    const NEWSLETTER_NOT_A_BOUNCE = 1;
    const NEWSLETTER_SOFTBOUNCE = 2;
    const NEWSLETTER_HARDBOUNCE = 3;
    const NEWSLETTER_UNSUBSCRIBE = 4;

    /**
     * Bounce level of the mail source specified
     * @var int @see \Ecodev\Newsletter\BounceHandler
     */
    private $bounceLevel = self::NEWSLETTER_NOT_A_BOUNCE;

    /**
     * The mail source
     * @var string
     */
    private $mailsource = null;

    /**
     * ObjecManager
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    private $objectManager;

    /**
     * The email concerned by the bounce if any
     * @var \Ecodev\Newsletter\Domain\Model\Email
     */
    private $email = null;

    /**
     * The recipient list concerned by the bounce if any
     * @var \Ecodev\Newsletter\Domain\Model\RecipientList
     */
    private $recipientList = null;

    /**
     * Matches for soft bounces
     */
    protected $soft = [
        '/mailbox is full/i',
        '/quota exceeded/i',
        '/Subject:\s*Delivery unsuccessful: Mailbox has exceeded the limit/i',
        '/over quota/i',
        '/Mailbox disk quota exceeded/i',
        '/recipient was unavailable to take delivery of the message/i',
        '/Subject:\s*Undelivered Mail Returned to Sender/i',
    ];

    /**
     *  Matches for hard bounces
     */
    protected $hard = [
        /* Anywhere in the mail */
        '/User unknown/',
        '/sorry to have to inform you that your message could not be delivered to one or more recipients./i',
        '/Delivery to the following recipients failed/i',
        '/Your message was automatically rejected by Sieve/i',
        '/sorry, no mailbox here by that name/i',
        '/550 no such/i',
        '/550 user/i',
        '/550 unknown/i',
        '/550 Invalid recipient/i',
        '/550 Host unknown/i',
        '/550 Address invalid/i',
        '/unknown or illegal alias/i',
        '/Unrouteable address/i',
        '/The following addresses had permanent fatal errors/i',
        '/qmail[\s\S]+this is a permanent error/i',
        '/no such user here/',
        /* On the subjectline */
        '/Subject:\s*Auto: Non existing e-mail/i',
        '/Subject:\s*Delivery Failure:/i',
        '/Subject:\s*Delivery Status Notification (Failure)/i',
        '/Subject:\s*Failed (mail|delivery|notice)/i',
        /* Both */
        '/Subject:\s*Delivery Status Notification[\s\S]+Failed/ix',
    ];

    /**
     * Fetch all email from Bounce Accounts and pipe each of them to cli/bounce.php
     */
    public static function fetchBouncedEmails()
    {
        // Check that th configured fetchmail is actually available
        $fetchmail = Tools::confParam('path_to_fetchmail');
        $foo = $exitStatus = null;
        exec("$fetchmail --version 2>&1", $foo, $exitStatus);
        if ($exitStatus) {
            throw new Exception("fetchmail is not available with path configured via Extension Manager '$fetchmail'. Install fetchmail or update configuration and try again.");
        }

        // Find all bounce accounts we need to check
        $fetchmailConfiguration = '';
        $servers = [];
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $bounceAccountRepository = $objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\BounceAccountRepository');
        foreach ($bounceAccountRepository->findAll() as $bounceAccount) {
            $fetchmailConfiguration .= $bounceAccount->getSubstitutedConfig() . "\n";
            $servers[] = $bounceAccount->getServer();
        }

        // Write a new fetchmailrc based on bounce accounts found
        $fetchmailHome = PATH_site . 'uploads/tx_newsletter';
        $fetchmailFile = "$fetchmailHome/fetchmailrc";
        file_put_contents($fetchmailFile, $fetchmailConfiguration);
        $fetchmailConfiguration = null; // Dont leave unencrypted values in memory around for too long.
        chmod($fetchmailFile, 0600);
        putenv("FETCHMAILHOME=$fetchmailHome");

        // Keep messages on server
        $keep = Tools::confParam('keep_messages') ? '--keep ' : '';

        // Execute fetchtmail and ask him to pipe emails to our cli/bounce.php
        $cli_dispatcher = PATH_typo3 . 'cli_dispatch.phpsh'; // This needs to be the absolute path of /typo3/cli_dispatch.phpsh
        foreach ($servers as $server) {
            $cmd = "$fetchmail -s $keep -m \"$cli_dispatcher newsletter_bounce\" $server";
            exec($cmd);
        }

        unlink($fetchmailFile);
    }

    /**
     * Constructor for bounce handler
     * @param string $mailsource
     */
    public function __construct($mailsource = '')
    {
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->analyze($mailsource);
    }

    /**
     * Analyze the given mail source to guess the bounce level
     * @param string $mailsource
     */
    protected function analyze($mailsource)
    {
        $this->mailsource = $mailsource;

        // We first assume it is not a bounce
        $this->bounceLevel = self::NEWSLETTER_NOT_A_BOUNCE;

        // Test the soft-bounce level
        foreach ($this->soft as $reg) {
            if (preg_match($reg, $this->mailsource)) {
                $this->bounceLevel = self::NEWSLETTER_SOFTBOUNCE;
            }
        }

        // Test the hard-bounce level
        foreach ($this->hard as $reg) {
            if (preg_match($reg, $this->mailsource)) {
                $this->bounceLevel = self::NEWSLETTER_HARDBOUNCE;
            }
        }
    }

    /**
     * Attempt to find the email in database which were bounced
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     */
    protected function findEmail()
    {
        global $TYPO3_DB;
        $this->email = null;
        $this->recipientList = null;

        if (preg_match_all('|Message-ID: <(.*)@.*>|', $this->mailsource, $match)) {
            // The last match is the authcode of the email sent
            $this->authCode = end($match[1]);

            // Find the recipientList and email UIDs according to authcode
            $rs = $TYPO3_DB->sql_query("
			SELECT tx_newsletter_domain_model_newsletter.recipient_list, tx_newsletter_domain_model_email.uid
			FROM tx_newsletter_domain_model_email
			INNER JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid)
			INNER JOIN tx_newsletter_domain_model_recipientlist ON (tx_newsletter_domain_model_newsletter.recipient_list = tx_newsletter_domain_model_recipientlist.uid)
			WHERE MD5(CONCAT(tx_newsletter_domain_model_email.uid, tx_newsletter_domain_model_email.recipient_address)) = '$this->authCode' AND recipient_list IS NOT NULL
			LIMIT 1");

            if (list($recipientListUid, $emailUid) = $TYPO3_DB->sql_fetch_row($rs)) {
                $emailRepository = $this->objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\EmailRepository');
                $this->email = $emailRepository->findByUid($emailUid);

                $recipientListRepository = $this->objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\RecipientListRepository');
                $this->recipientList = $recipientListRepository->findByUid($recipientListUid);
            }
        }
    }

    /**
     * Dispatch actions to take according to current bounce level
     */
    public function dispatch()
    {
        $this->findEmail();

        // If couldn't find the original email we cannot do anything
        if (!$this->email) {
            Tools::getLogger(__CLASS__)->warning('Bounced email found but cannot find corresponding record in database. Skipped.');

            return;
        }

        if ($this->bounceLevel != self::NEWSLETTER_NOT_A_BOUNCE) {
            if ($this->recipientList) {
                $this->recipientList->registerBounce($this->email->getRecipientAddress(), $this->bounceLevel);
            }

            $this->email->setBounceTime(new DateTime());
            $emailRepository = $this->objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\EmailRepository');
            $emailRepository->updateNow($this->email);
        }

        Tools::getLogger(__CLASS__)->info('Bounced email found with bounce level ' . $this->bounceLevel);
    }
}

<?php

namespace Ecodev\Newsletter;

use DateTime;
use Ecodev\Newsletter\Utility\EmailParser;

/**
 * Handle bounced emails. Fetch them, analyse them and take approriate actions.
 */
class BounceHandler
{
    /**
     * The email parser
     * @var Utility\EmailParser
     */
    private $emailParser;

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
     * Fetch all email from Bounce Accounts and pipe each of them to cli/bounce.php
     */
    public static function fetchBouncedEmails()
    {
        // Check that th configured fetchmail is actually available
        $fetchmail = Tools::confParam('path_to_fetchmail');
        $foo = $exitStatus = null;
        exec("$fetchmail --version 2>&1", $foo, $exitStatus);
        if ($exitStatus) {
            throw new \Exception("fetchmail is not available with path configured via Extension Manager '$fetchmail'. Install fetchmail or update configuration and try again.");
        }

        // Find all bounce accounts we need to check
        $fetchmailConfiguration = '';
        $servers = [];
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        $bounceAccountRepository = $objectManager->get(\Ecodev\Newsletter\Domain\Repository\BounceAccountRepository::class);
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
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

        $this->emailParser = new EmailParser();
        $this->emailParser->parse($mailsource);
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

        $authCode = $this->emailParser->getAuthCode();
        if ($authCode) {

            // Find the recipientList and email UIDs according to authcode
            $rs = $TYPO3_DB->sql_query("
			SELECT tx_newsletter_domain_model_newsletter.recipient_list, tx_newsletter_domain_model_email.uid
			FROM tx_newsletter_domain_model_email
			INNER JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid)
			INNER JOIN tx_newsletter_domain_model_recipientlist ON (tx_newsletter_domain_model_newsletter.recipient_list = tx_newsletter_domain_model_recipientlist.uid)
			WHERE tx_newsletter_domain_model_email.auth_code = '$authCode' AND recipient_list IS NOT NULL
			LIMIT 1");

            if (list($recipientListUid, $emailUid) = $TYPO3_DB->sql_fetch_row($rs)) {
                $emailRepository = $this->objectManager->get(\Ecodev\Newsletter\Domain\Repository\EmailRepository::class);
                $this->email = $emailRepository->findByUid($emailUid);

                $recipientListRepository = $this->objectManager->get(\Ecodev\Newsletter\Domain\Repository\RecipientListRepository::class);
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

        $bounceLevel = $this->emailParser->getBounceLevel();
        if ($bounceLevel != EmailParser::NEWSLETTER_NOT_A_BOUNCE) {
            if ($this->recipientList) {
                $this->recipientList->registerBounce($this->email->getRecipientAddress(), $bounceLevel);
            }

            $this->email->setBounceTime(new DateTime());
            $emailRepository = $this->objectManager->get(\Ecodev\Newsletter\Domain\Repository\EmailRepository::class);
            $emailRepository->update($this->email);
        }

        Tools::getLogger(__CLASS__)->info('Bounced email found with bounce level ' . $bounceLevel);
    }
}

<?php

namespace Ecodev\Newsletter\Utility;

/**
 * Parse a raw email source to find its bounce level and authCode
 */
class EmailParser
{
    const NEWSLETTER_NOT_A_BOUNCE = 1;
    const NEWSLETTER_SOFTBOUNCE = 2;
    const NEWSLETTER_HARDBOUNCE = 3;
    const NEWSLETTER_UNSUBSCRIBE = 4;

    /**
     * Bounce level of the mail source specified
     * @var int @see \Ecodev\Newsletter\BounceHandler
     */
    private $bounceLevel;

    /**
     * The email source
     * @var string
     */
    private $emailSource;

    /**
     * @var array Patterns for soft bounces
     */
    private $softBouncePatterns = [
        '/mailbox is full/i',
        '/quota exceeded/i',
        '/Subject:\s*Delivery unsuccessful: Mailbox has exceeded the limit/i',
        '/over quota/i',
        '/Mailbox disk quota exceeded/i',
        '/recipient was unavailable to take delivery of the message/i',
        '/Subject:\s*Undelivered Mail Returned to Sender/i',
    ];

    /**
     * @var array Patterns for hard bounces
     */
    private $hardBouncePatterns = [
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
    private $unsubscribePattern = '/Subject:\s*unsubscribe-([\da-f]{32})/i';

    /**
     * @param string $emailSource
     */
    public function parse($emailSource)
    {
        $this->emailSource = $emailSource;
        $this->authCode = null;
        $this->parseBounceLevel();
        $this->parseAuthCode();
    }

    public function getBounceLevel()
    {
        return $this->bounceLevel;
    }

    public function getAuthCode()
    {
        return $this->authCode;
    }

    /**
     * Analyze the given mail source to guess the bounce level
     */
    private function parseBounceLevel()
    {
        // We first assume it is not a bounce
        $this->bounceLevel = self::NEWSLETTER_NOT_A_BOUNCE;

        // Test the soft-bounce level
        foreach ($this->softBouncePatterns as $reg) {
            if (preg_match($reg, $this->emailSource)) {
                $this->bounceLevel = self::NEWSLETTER_SOFTBOUNCE;
            }
        }

        // Test the hard-bounce level
        foreach ($this->hardBouncePatterns as $reg) {
            if (preg_match($reg, $this->emailSource)) {
                $this->bounceLevel = self::NEWSLETTER_HARDBOUNCE;
            }
        }

        if (preg_match($this->unsubscribePattern, $this->emailSource, $matches)) {
            $this->bounceLevel = self::NEWSLETTER_UNSUBSCRIBE;
            $this->authCode = $matches[1];
        }
    }

    private function parseAuthCode()
    {
        if (!$this->authCode && preg_match_all('|Message-ID: <(.*)@.*>|', $this->emailSource, $match)) {
            // The last match is the authcode of the email sent
            $this->authCode = end($match[1]);
        }
    }
}

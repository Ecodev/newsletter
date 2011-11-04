<?php

class tx_newsletter_bouncehandler
{	
	const NEWSLETTER_NOT_A_BOUNCE = 1;
	const NEWSLETTER_SOFTBOUNCE = 2;
	const NEWSLETTER_HARDBOUNCE = 3;
	const NEWSLETTER_UNSUBSCRIBE = 4;
	
	/**
	 * Bounce level of the mail source specified
	 * @var integer @see tx_newsletter_bouncehandler
	 */
	private $status = self::NEWSLETTER_NOT_A_BOUNCE;
	
	/**
	 * The mail source
	 * @var string
	 */
	private $mailsource = null;
	
	/**
	 * The email concerned by the bounce if any
	 * @var Tx_Newsletter_Domain_Model_Email
	 */
	private $email = null;
	
	/**
	 * The recipient list concerned by the bounce if any
	 * @var Tx_Newsletter_Domain_Model_RecipientList
	 */
	private $recipientList = null;
	
	/**
	 * Matches for soft bounces
	 */
	protected $soft = array(
      '/mailbox is full/i',
      '/quota exceeded/i',
      '/Subject:\s*Delivery unsuccessful: Mailbox has exceeded the limit/i',
      '/over quota/i',
      '/Mailbox disk quota exceeded/i',
      '/recipient was unavailable to take delivery of the message/i',
      '/Subject:\s*Undelivered Mail Returned to Sender/i',
	);

	/**
	 *  Matches for hard bounces
	 */
	protected $hard = array(
	/* Any where in the mail */
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
	);

	/**
	 * Constructor for bounce handler
	 * @param string $mailsource
	 */
	function __construct($mailsource = '') {
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
		$this->status = self::NEWSLETTER_NOT_A_BOUNCE;

		// Test the soft-bounce level
		foreach ($this->soft as $reg) {
			if (preg_match($reg, $this->mailsource)) {
				$this->status = self::NEWSLETTER_SOFTBOUNCE;
			}
		}

		// Test the hard-bounce level
		foreach ($this->hard as $reg) {
			if (preg_match($reg, $this->mailsource)) {
				$this->status = self::NEWSLETTER_HARDBOUNCE;
			}
		}
	}
	
	/**
	 * Attempt to find the email in database which were bounced 
	 */
	protected function findEmail()
	{
		global $TYPO3_DB;
		$this->email = null;
		$this->recipientList = null;
		
		if (preg_match_all('|Message-ID: <(.*)@.*>|', $this->mailsource, $match))
		{
			// The last match is the authcode of the email sent
			$this->authCode = end($match[1]);
				
			// Tell the target that he opened the email
			$rs = $TYPO3_DB->sql_query("
			SELECT tx_newsletter_domain_model_newsletter.recipient_list, tx_newsletter_domain_model_email.uid
			FROM tx_newsletter_domain_model_email
			LEFT JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid)
			LEFT JOIN tx_newsletter_domain_model_recipientlist ON (tx_newsletter_domain_model_newsletter.recipient_list = tx_newsletter_domain_model_recipientlist.uid) 
			WHERE MD5(CONCAT(tx_newsletter_domain_model_email.uid, tx_newsletter_domain_model_email.recipient_address)) = '$this->authCode' AND recipient_list IS NOT NULL
			LIMIT 1");
				
			if (list($recipientListUid, $emailUid) = $TYPO3_DB->sql_fetch_row($rs)) {
				$emailRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_EmailRepository');
				$this->email = $emailRepository->findByUid($emailUid);
				
				$recipientListRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_RecipientListRepository');
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
		if (!$this->email)
			return;
			
		if ($this->status != self::NEWSLETTER_NOT_A_BOUNCE)
		{
			if ($this->recipientList)
			{
				$this->recipientList->registerBounce($this->email->getRecipientAddress(), $this->status);
			}

			$this->email->setBounceTime(new DateTime());
			$emailRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_EmailRepository');
			$emailRepository->updateNow($this->email);
		}
	}
}


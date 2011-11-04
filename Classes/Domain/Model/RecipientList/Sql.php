<?php

/**
 * This is the basic SQL related newsletter target. Methods implemented with DB calls using SQL query defined by end-user.
 * Extend this class to create newsletter targets which extracts recipients from the database.
 *
 */
class Tx_Newsletter_Domain_Model_RecipientList_Sql extends Tx_Newsletter_Domain_Model_RecipientList {

	/**
	 * sqlStatement
	 *
	 * @var string $sqlStatement
	 */
	protected $sqlStatement;

	/**
	 * sqlRegisterBounce
	 *
	 * @var string $sqlRegisterBounce
	 */
	protected $sqlRegisterBounce;

	/**
	 * sqlRegisterOpen
	 *
	 * @var string $sqlRegisterOpen
	 */
	protected $sqlRegisterOpen;

	/**
	 * sqlRegisterClick
	 *
	 * @var string $sqlRegisterClick
	 */
	protected $sqlRegisterClick;

	/**
	 * Setter for sqlStatement
	 *
	 * @param string $sqlStatement sqlStatement
	 * @return void
	 */
	public function setSqlStatement($sqlStatement) {
		$this->sqlStatement = $sqlStatement;
	}

	/**
	 * Getter for sqlStatement
	 *
	 * @return string sqlStatement
	 */
	public function getSqlStatement() {
		return $this->sqlStatement;
	}

	/**
	 * Setter for sqlRegisterBounce
	 *
	 * @param string $sqlRegisterBounce sqlRegisterBounce
	 * @return void
	 */
	public function setSqlRegisterBounce($sqlRegisterBounce) {
		$this->sqlRegisterBounce = $sqlRegisterBounce;
	}

	/**
	 * Getter for sqlRegisterBounce
	 *
	 * @return string sqlRegisterBounce
	 */
	public function getSqlRegisterBounce() {
		return $this->sqlRegisterBounce;
	}

	/**
	 * Setter for sqlRegisterOpen
	 *
	 * @param string $sqlRegisterOpen sqlRegisterOpen
	 * @return void
	 */
	public function setSqlRegisterOpen($sqlRegisterOpen) {
		$this->sqlRegisterOpen = $sqlRegisterOpen;
	}

	/**
	 * Getter for sqlRegisterOpen
	 *
	 * @return string sqlRegisterOpen
	 */
	public function getSqlRegisterOpen() {
		return $this->sqlRegisterOpen;
	}

	/**
	 * Setter for sqlRegisterClick
	 *
	 * @param string $sqlRegisterClick sqlRegisterClick
	 * @return void
	 */
	public function setSqlRegisterClick($sqlRegisterClick) {
		$this->sqlRegisterClick = $sqlRegisterClick;
	}

	/**
	 * Getter for sqlRegisterClick
	 *
	 * @return string sqlRegisterClick
	 */
	public function getSqlRegisterClick() {
		return $this->sqlRegisterClick;
	}
	
	function init() {
		$sql = trim($this->getSqlStatement());
		
		// Inject dummy SQL statement, just for fun !
		if (!$sql)
		{
			$sql = 'SELECT email FROM be_users WHERE uid = -1';
		}
		
		$this->data = $GLOBALS['TYPO3_DB']->sql_query($sql);
	}
	
	/**
	 * Fetch a recipient from the sql-record set. This also computes some commonly used values, 
	 * such as plain_only and language.
	 *
	 * @return	array	Recipient with user data.
	 */
	function getRecipient() {
		$r = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->data);
		if (is_array($r)) {
			if (!isset($r['plain_only'])) {
				$r['plain_only'] = $this->isPlainOnly();
			}

			if (!isset($r['L'])) {
				$r['L'] = $this->getLang();
			}
        
			return $r;
		} else {
			return false;
		}
	}   
   
	function getCount() {
		return $GLOBALS['TYPO3_DB']->sql_num_rows($this->data);
	}
   
	function getError() {
		return $GLOBALS['TYPO3_DB']->sql_error($this->data);
	}
	   
	/**
	 * Execute the SQL defined by the user to disable a recipient.
	 *
	 * @param string $email of the address that has failed.
	 * @param integer Status of the bounce
	 * @return bool Status of the success of the removal.
	 */
	function registerBounce($email, $bounce_type) {
		global $TYPO3_DB;
		
		$sql = str_replace(array(
				'###EMAIL###',
				'###BOUNCE_TYPE###',
				'###BOUNCE_TYPE_SOFT###',
				'###BOUNCE_TYPE_HARD###',
				'###BOUNCE_TYPE_UNSUBSCRIBE###',
			),
			array(
				$TYPO3_DB->fullQuoteStr($email),
				$bounce_type,
				tx_newsletter_bouncehandler::NEWSLETTER_SOFTBOUNCE,
				tx_newsletter_bouncehandler::NEWSLETTER_HARDBOUNCE,
				tx_newsletter_bouncehandler::NEWSLETTER_UNSUBSCRIBE,
			),
			$this->getSqlRegisterBounce());
		
		if ($sql) {
			$TYPO3_DB->sql_query($sql);
			return $TYPO3_DB->sql_affected_rows();
		} else {
			return false;
		}
	}
	
	/**
	 * Execute the SQL defined by the user to register that the email was open
	 *
	 * @param string $email the email address of the recipient (who opened the mail)
	 * @return	void
	 */
	function registerOpen($email) {
		global $TYPO3_DB;
		
		$sql = str_replace('###EMAIL###', $TYPO3_DB->fullQuoteStr($email), $this->getSqlRegisterOpen());
	      
		if ($sql) {
			$TYPO3_DB->sql_query($sql);
			return $TYPO3_DB->sql_affected_rows();
		} else {
			return false;
		}
	}

	/**
	 * Execute the SQL defined by the user to whenever the recipient has clicked a link via click.php
	 *
	 * @param string $email the email address of the recipient
	 * @return	void
	 */
	function registerClick($email) {
		global $TYPO3_DB;
		
		$sql = str_replace('###EMAIL###', $TYPO3_DB->fullQuoteStr($email), $this->getSqlRegisterClick());
	      
		if ($sql) {
			$TYPO3_DB->sql_query($sql);
			return $TYPO3_DB->sql_affected_rows();
		} else {
			return false;
		}
	}
}



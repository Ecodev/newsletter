<?php

/**
 * This is the basic SQL related newsletter target. Methods implemented with DB calls using SQL query defined by end-user.
 * Extend this class to create newsletter targets which extracts recipients from the database.
 *
 */
class Tx_Newsletter_Domain_Model_RecipientList_Sql extends Tx_Newsletter_Domain_Model_RecipientList {
	
	function init() {
		$sql = trim($this->fields['sql_statement']);
		
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
				$r['plain_only'] = $this->fields['plain_only'];
			}

			if (!isset($r['L'])) {
				$r['L'] = $this->fields['lang'];
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
		
		$sql = str_replace(array('###EMAIL###', '###BOUNCE_TYPE###'), array($email, $bounce_type), $this->fields['sql_register_bounce']);
	      
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
		
		$sql = str_replace('###EMAIL###', $email, $this->fields['sql_register_open']);
	      
		if ($sql) {
			$TYPO3_DB->sql_query($sql);
			return $TYPO3_DB->sql_affected_rows();
		} else {
			return false;
		}
	}

	/**
	 * Execute the SQL defined by the user to when ever the recipient has clicked a link via click.php
	 *
	 * @param string $email the email address of the recipient
	 * @return	void
	 */
	function registerClick($email) {
		global $TYPO3_DB;
		
		$sql = str_replace('###EMAIL###', $email, $this->fields['sql_register_click']);
	      
		if ($sql) {
			$TYPO3_DB->sql_query($sql);
			return $TYPO3_DB->sql_affected_rows();
		} else {
			return false;
		}
	}
}

?>

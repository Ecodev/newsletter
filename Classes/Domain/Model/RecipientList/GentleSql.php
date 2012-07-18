<?php

/**
 * This is a more gentle version on the generic sql-driven target. It is dependant on integer field tx_newsletter_bounce
 * on the $this->getTableName() table.
 *
 * @abstract
 */
abstract class Tx_Newsletter_Domain_Model_RecipientList_GentleSql extends Tx_Newsletter_Domain_Model_RecipientList_Sql {
	
	/**
	 * Returns the tablename to work with
	 * @return string 
	 */
	abstract protected function getTableName();
	
	/**
	 * This increases the bounce-counter each time a mail has bounced.
	 * Hard bounces count more that soft ones. After 2 hards or 10 softs the user will be disabled. 
	 * You should be able to reset then in the backend
	 *
	 * @global t3lib_DB $TYPO3_DB
	 * @param string $email the email address of the recipient
	 * @param integer $bounceLevel This is the level of the bounce.
	 * @return boolean Success of the bounce-handling.
	 */
	function registerBounce($email, $bounceLevel) {
		global $TYPO3_DB;
		
		$increment = 0;
		switch ($bounceLevel) {
			case Tx_Newsletter_BounceHandler::NEWSLETTER_UNSUBSCRIBE:
				$increment = 10;
				break;
			case Tx_Newsletter_BounceHandler::NEWSLETTER_HARDBOUNCE:
				$increment = 5;
				break;
			case Tx_Newsletter_BounceHandler::NEWSLETTER_SOFTBOUNCE:
				$increment = 1;
				break;
		}
		
		if ($increment)
		{
			$TYPO3_DB->sql_query("UPDATE " . $this->getTableName() . "
						SET tx_newsletter_bounce = tx_newsletter_bounce + $increment
						WHERE email = '$email'");
			return $TYPO3_DB->sql_affected_rows();
		}
		
		return false;
	}

	/**
	 * This is a default action for registered clicks.
	 * Here we just reset the bounce counter. If the user reads the mail, it must have succeded. 
	 * It can also be used for marketing or statistics purposes 
	 *
	 * @param string $email the email address of the recipient
	 */
	function registerClick($email) {
		$GLOBALS['TYPO3_DB']->sql_query ("UPDATE " . $this->getTableName() . "
							SET tx_newsletter_bounce = 0
							WHERE email = '$email'");
	}

	/**
	 * Like the registerClick()-method, but just for embedded spy-image.
	 *
	 * @param string $email the email address of the recipient
	 */
	function registerOpen($email) {
		$GLOBALS['TYPO3_DB']->sql_query ("UPDATE " . $this->getTableName() . "
							SET tx_newsletter_bounce = 0
							WHERE email = '$email'");
	}
}

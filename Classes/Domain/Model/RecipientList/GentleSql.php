<?php

/**
 * This is a more gentle version on the generic sql-driven target. It is dependant on integer field tx_newsletter_bounce
 * on the $this->tableName table.
 *
 * @abstract
 */
class Tx_Newsletter_Domain_Model_RecipientList_GentleSql extends Tx_Newsletter_Domain_Model_RecipientList_Sql {
	/**
	 * This increases the bounce-counter each time a mail has bounced.
	 * Hard bounces count more that soft ones. After 2 hards or 10 softs the user will be disabled. 
	 * You should be able to reset then in the backend
	 *
	 * @param	integer		This is the uid of the receiver.
	 * @param	integer		This is the level of the bounce.
	 * @return	bool		Success of the bounce-handling.
	 */
	function disableReceiver($uid, $bounce_level) {
		global $TYPO3_DB;

		switch ($bounce_level) {
			case	NEWSLETTER_HARDBOUNCE:
				$TYPO3_DB->sql_query("UPDATE $this->tableName 
							SET tx_newsletter_bounce = tx_newsletter_bounce + 5
							WHERE uid = $uid");

				return $TYPO3_DB->sql_affected_rows();

			case	NEWSLETTER_SOFTBOUNCE:
				$TYPO3_DB->sql_query("UPDATE $this->tableName 
							SET tx_newsletter_bounce = tx_newsletter_bounce + 1
							WHERE uid = $uid");
				return $TYPO3_DB->sql_affected_rows();

			default:
				return false;
		}
	}

	/**
	 * This is a default action for registered clicks.
	 * Here we just reset the bounce counter. If the user reads the mail, it must have succeded. 
	 * It can also be used for marketing og statistics purposes 
	 *
	 * @param	integer		The uid of the receiver.
	 */
	function registerClick($uid) {
		$GLOBALS['TYPO3_DB']->sql_query ("UPDATE $this->tableName
							SET tx_newsletter_bounce = 0
							WHERE uid = $uid");
	}

	/**
	 * Like the registerClick()-method, but just for embedded spy-image.
	 *
	 * @param	integer		The uid of the receiver.
	 */
	function registerOpen($uid) {
		$GLOBALS['TYPO3_DB']->sql_query ("UPDATE $this->tableName
							SET tx_newsletter_bounce = 0
							WHERE uid = $uid");
	}
}

?>

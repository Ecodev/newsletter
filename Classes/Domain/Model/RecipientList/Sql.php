<?php

/**
 * This is the basic SQL related newsletter target. Methods implemented with DB calls.
 * Extend this class to create newsletter targets which extracts records from the database.
 *
 * @abstract
 */

class Tx_Newsletter_Domain_Model_RecipientList_Sql extends Tx_Newsletter_Domain_Model_RecipientList {
	var $tableName = 'undefinedtable';
	
	/**
	 * Fetch a record from the sql-record set. This also computes some commonly used values, 
	 * such as plain_only and tableName.
	 *
	 * @return	array	Record with user data.
	 */
	function getRecord() {
		$r = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->data);
		if (is_array($r)) {
			if (!isset($r['plain_only'])) {
				$r['plain_only'] = $this->fields['plain_only'];
			}

			if (!isset($r['L'])) {
				$r['L'] = $this->fields['lang'];
			}
      
			if ($this->tableName <> 'undefinedtable') {
				$r['tableName'] = $this->tableName;
			}
        
			return $r;
		} else {
			return false;
		}
	}   
   
	function getCount() {
		return $GLOBALS['TYPO3_DB']->sql_num_rows($this->data);
	}
   
	function resetTarget() {
		$GLOBALS['TYPO3_DB']->sql_data_seek($this->data,0);
	}
   
	function getError() {
		return $GLOBALS['TYPO3_DB']->sql_error($this->data);
	}
	   
	/**
	 * Here you can implement database operation done when an email address has failed. 
	 * It is not mandatory to do anything, but here is a sensible default provided for database-provided receivers. 
	 * IF YOU DO NOT WANT TO DELETE YOUR RECORDS, PLEASE, PLEASE OVERRIDE THIS METHOD WITH SOMETHING MORE GENTLE. 
	 * DONT BLAME ME FOR LOST DATA. Alternatively you can inherit from the tx_newsletter_target:gentlesql class
	 * instead.
	 *
	 * @param   integer    Uid of the address that has failed.
	 * @param   integer    Status of the bounce
	 * @return  bool       Status of the success of the removal.
	 */
	function disableReceiver($uid, $bounce_type) {
		global $TYPO3_DB;
	      
		if ($this->tableName <> 'undefinedtable') {
			$TYPO3_DB->sql_query("DELETE FROM $this->tableName WHERE uid = $uid");
			return $TYPO3_DB->sql_affected_rows();
		} else {
			return false;
		}                                    
	}      
}

?>

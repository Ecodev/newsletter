<?php

/**
 * This is the basic class for extracting recipient from other data sources than the database.
 * Here the internal datastructure is an array.
 * You might extend your class from this if you use external sources.
 *
 * @abstract
 */

abstract class Tx_Newsletter_Domain_Model_RecipientList_Array extends Tx_Newsletter_Domain_Model_RecipientList {
   
	function getRecipient() {
		$r = current($this->data);
		next($this->data);
     
		if (is_array($r)) {
			if (!isset($r['plain_only'])) {
				$r['plain_only'] = $this->fields['plain_only'];
			}
      
			return $r;
		} else {
			return false;
		}
	}
   
	function getCount() {
		return count($this->data);
	}
   
	function getError() {
		if (!is_array($this->data)) {
			return "Not an array";
		}
       
		if (count($this->data) == 0) {
			return "No data fetched";
		}
	}
   
	function registerBounce($uid, $bounce_type) {
		/* We dont have something reasonable to do here, since we dont have a table..   maybe it can be extented in some specific setup..  */
		return false;
	}
}



<?php

require_once(t3lib_extMgm::extPath('newsletter').'class.tx_tcdirectmail_target.php');

/**
 * This is the basic class for extracting record from other data sources than the database.
 * Here the internal datastructure is an array.
 * You might extend your class from this if you use external sources.
 *
 * @abstract
 */

class tx_tcdirectmail_target_array extends tx_tcdirectmail_target {
	function resetTarget() {
		reset($this->data);
	}
   
	function getRecord() {
		$r = current($this->data);
		next($this->data);
     
		if (is_array($r)) {
			if (!isset($r['plain_only'])) {
				$r['plain_only'] = $this->fields['plain_only'];
			}
      
			if (isset($r['uid'])) {
				$r['authCode'] = t3lib_div::stdAuthCode($r['uid']);
			} else {
				$r['authCode'] = t3lib_div::stdAuthCode($r['email']);
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
   
	function disableReceiver($uid, $bounce_type) {
		/* We dont have something reasonable to do here, since we dont have a table..   maybe it can be extented in some specific setup..  */
		return false;
	}
}

?>

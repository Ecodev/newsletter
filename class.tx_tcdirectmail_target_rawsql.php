<?php

require_once (t3lib_extMgm::extPath('newsletter').'class.tx_tcdirectmail_target_sql.php');
class tx_tcdirectmail_target_rawsql extends tx_tcdirectmail_target_sql { 
	function init() {
		if ($this->fields['rawsql'] == '') {
			$sql = 'SELECT * FROM tt_address WHERE uid = -1';
		} else {
			$sql = $this->fields['rawsql'];
		}
		$this->data = $GLOBALS['TYPO3_DB']->sql_query($sql);
	}
}

?>

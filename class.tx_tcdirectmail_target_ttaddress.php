<?php

require_once (t3lib_extMgm::extPath('newsletter').'class.tx_tcdirectmail_target_gentlesql.php');
class tx_tcdirectmail_target_ttaddress extends tx_tcdirectmail_target_gentlesql { 
	var $tableName = 'tt_address';

	function init() {
		$config = explode(',',$this->fields['ttaddress']);
		$config[] = -1;
		$config = array_filter($config);
     
		$this->data = $GLOBALS['TYPO3_DB']->sql_query(
			"SELECT DISTINCT tt_address.uid,name,address,phone,fax,email,tt_address.title,zip,city,country,www,company,pages.title AS pages_title
				FROM pages
				INNER JOIN tt_address ON pages.uid = tt_address.pid
				WHERE pages.uid IN (".implode(',',$config).") 
				AND email != '' 
				AND pages.deleted = 0 
				AND pages.hidden = 0 
				AND tt_address.deleted = 0
				AND tt_address.hidden = 0
				AND tx_tcdirectmail_bounce < 10");
	}
}

?>

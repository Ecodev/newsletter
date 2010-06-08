<?php
require_once (t3lib_extMgm::extPath('newsletter').'class.tx_tcdirectmail_target_gentlesql.php');

class tx_tcdirectmail_target_beusers extends tx_tcdirectmail_target_gentlesql { 
	var $tableName = 'be_users';

	function init() {
		$config = explode(',',$this->fields['beusers']);
		$config[] = -1;       
		$config = array_filter($config);
                           
		$this->data = $GLOBALS['TYPO3_DB']->sql_query(
			"SELECT uid,email,realName,username,lang,admin FROM be_users 
				WHERE uid IN (".implode(',',$config).") 
				AND email <> '' 
				AND disable = 0
				AND tx_tcdirectmail_bounce < 10");
	}
}
   
?>

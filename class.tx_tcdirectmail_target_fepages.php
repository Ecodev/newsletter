<?php

require_once (t3lib_extMgm::extPath('newsletter').'class.tx_tcdirectmail_target_sql.php');
class tx_tcdirectmail_target_fepages extends tx_tcdirectmail_target_sql { 
	var $tableName = 'fe_users';

	function init () {
		$config = explode(',',$this->fields['fepages']);
		$config[] = -1;
		$config = array_filter($config);
       
		$this->data = $GLOBALS['TYPO3_DB']->sql_query(
			"SELECT DISTINCT fe_users.uid,name,address,telephone,fax,email,username,fe_users.title,zip,city,country,www,company,pages.title as pages_title
				FROM pages
				INNER JOIN fe_users ON pages.uid = fe_users.pid
				WHERE pages.uid IN (".implode(',',$config).") 
				AND email != '' 
				AND pages.deleted = 0 
				AND pages.hidden = 0 
				AND fe_users.disable = 0
				AND fe_users.deleted = 0
				AND tx_tcdirectmail_bounce < 10");
	}
}

?>

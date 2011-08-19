<?php

class Tx_Newsletter_Domain_Model_RecipientList_BeUsers extends Tx_Newsletter_Domain_Model_RecipientList_GentleSql { 
	var $tableName = 'be_users';

	function init() {
		$config = explode(',', $this->fields['be_users']);
		$config[] = -1;       
		$config = array_filter($config);
                           
		$this->data = $GLOBALS['TYPO3_DB']->sql_query(
			"SELECT uid,email,realName,username,lang,admin FROM be_users 
				WHERE uid IN (".implode(',',$config).") 
				AND email <> '' 
				AND disable = 0
				AND tx_newsletter_bounce < 10");
	}
}
   


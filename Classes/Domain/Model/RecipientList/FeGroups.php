<?php

class Tx_Newsletter_Domain_Model_RecipientList_FeGroups extends Tx_Newsletter_Domain_Model_RecipientList_GentleSql { 
	var $tableName = 'fe_users';

	function init () {
		$groups = explode(',',$this->fields['fegroups']);
		$groups[] = -1;
		$groups = array_filter($groups);
       
       
		$this->data = $GLOBALS['TYPO3_DB']->sql_query(
			"SELECT DISTINCT fe_users.uid,name,address,telephone,fax,email,username,fe_users.title,zip,city,country,www,company,fe_groups.title as group_title
				FROM fe_groups, fe_users
				WHERE fe_groups.uid IN (".implode(',',$groups).") 
				AND FIND_IN_SET(fe_groups.uid, fe_users.usergroup)
				AND email != '' 
				AND fe_groups.deleted = 0 
				AND fe_groups.hidden = 0 
				AND fe_users.disable = 0
				AND fe_users.deleted = 0
				AND tx_newsletter_bounce < 10");
	}
}

?>

<?php

class Tx_Newsletter_Domain_Model_RecipientList_RawSql extends Tx_Newsletter_Domain_Model_RecipientList_Sql { 
	function init() {
		$sql = trim($this->fields['sql_statement']);
		
		// Inject dummy SQL statement, just for fun !
		if (!$sql)
		{
			$sql = 'SELECT email FROM be_users WHERE uid = -1';
		}
		
		$this->data = $GLOBALS['TYPO3_DB']->sql_query($sql);
	}
}

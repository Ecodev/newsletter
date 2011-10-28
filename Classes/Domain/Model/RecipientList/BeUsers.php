<?php

class Tx_Newsletter_Domain_Model_RecipientList_BeUsers extends Tx_Newsletter_Domain_Model_RecipientList_GentleSql {
	
	/**
	 * beUsers
	 *
	 * @var string $beUsers
	 */
	protected $beUsers;

	/**
	 * Setter for beUsers
	 *
	 * @param string $beUsers beUsers
	 * @return void
	 */
	public function setBeUsers($beUsers) {
		$this->beUsers = $beUsers;
	}

	/**
	 * Getter for beUsers
	 *
	 * @return string beUsers
	 */
	public function getBeUsers() {
		return $this->beUsers;
	}
	
	/**
	 * Returns the tablename to work with
	 * @return string 
	 */
	protected function getTableName() {
		return 'be_users';
	}

	function init() {
		$config = explode(',', $this->getBeUsers());
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
   


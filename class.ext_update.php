<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Fabien Udriot <fabien.udriot@ecodev.ch>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Class for updating the data query
 *
 * @author		Fabien Udriot <fabien.udriot@ecodev.ch>
 * @package		TYPO3
 * @subpackage	tx_newsletter
 *
 * $Id$
 */
class ext_update {

	/**
	 *
	 * @var array
	 */
	private $tablesToCheck = array('pages', 'fe_users', 'be_users');

	/**
	 * Main function, returning the HTML content of the module
	 *
	 * @return	string	HTML to display
	 */
	function main() {
		global $TYPO3_DB;

		// Action! Makes the necessary update
		$update = t3lib_div::_GP('submitButton');
		// The update button was clicked
		if (!empty($update)) {

			// Rename tables
			$tablesToRename = $this->getTablesToRename();
			foreach ($tablesToRename as $tableName) {
				$targetName = $this->getTargetName($tableName);

				if ($targetName) {
					$request = 'RENAME TABLE ' . $tableName . ' TO ' . $targetName;
					$TYPO3_DB->sql_query($request);
				}
			}

			// Rename fields
			$this->tablesToCheck = array('pages', 'fe_users', 'be_users');
			foreach ($this->tablesToCheck as $tableName) {
				$fieldsToRename = $this->getFieldsToRename($tableName);
				$fields = $TYPO3_DB->admin_get_fields($tableName);

				foreach ($fieldsToRename as $fieldName) {
					$targetField = str_replace('tcdirectmail', 'newsletter', $fieldName);
					$type = $fields[$fieldName]['Type'];
					$request = 'ALTER TABLE ' . $tableName . ' CHANGE ' . $fieldName . ' ' . $targetField . ' ' . $type;
					$TYPO3_DB->sql_query($request);
				}
			}

//			$tceData = array('tx_newsletter_filters' => array());
//			foreach ($updateList as $uid => $newConfiguration) {
//				$tceData['tx_newsletter_filters'][$uid] = array('configuration' => $newConfiguration);
//			}
//			$tce = t3lib_div::makeInstance('t3lib_TCEmain');
//			$tce->stripslashes_values = 0;
//			$tce->start($tceData, array());
//			$tce->process_datamap();
		}

		// Defines the content to be displayed on the EM interface
		$content = '<h2>Check for old tables names</h2>';
		$hasUpdate = FALSE;
		/////////////////////////////////////
		// Get all tables of the database to be checked against tx_directmail's name
		$tablesToRename = $this->getTablesToRename();

		if (!empty($tablesToRename)) {
			$content .= '<p>The following tables need to be renamed.</p>';
			$content .= '<ul><li>' . implode('</li><li>', $tablesToRename) . '</li></ul>';
			$hasUpdate = TRUE;
		}

		/////////////////////////////////////
		// Get all fields of certain tables to be checked against tx_directmail's name
		$this->tablesToCheck = array('pages', 'fe_users', 'be_users');
		foreach ($this->tablesToCheck as $tableName) {
			$fieldsToRename = $this->getFieldsToRename($tableName);

			if (!empty($fieldsToRename)) {
				$content .= '<p>The following fields need to be renamed in table "' . $tableName . '".</p>';
				$content .= '<ul><li>' . implode('</li><li>', $fieldsToRename) . '</li></ul>';
				$hasUpdate = TRUE;
			}

		}

		if (!$hasUpdate) {
			$content .= '<p>No tables / fields need to be changed</p>';
		}
		else {
			// Display update form, if the update button was not already clicked
			$content .= '<form name="updateForm" action="" method ="post">';
			$content .= '<p><input type="submit" name="submitButton" value ="Update"></p>';
			$content .= '</form>';

		}

		return $content;
		#$res = $TYPO3_DB->exec_SELECTquery('uid, title, configuration', 'tx_newsletter_filters', "configuration <> ''");
//		while ($row = $TYPO3_DB->sql_fetch_assoc($res)) {

	}

	/**
	 * Returns the tables to be renamed
	 *
	 * @global t3lib_DB $TYPO3_DB
	 * @return array
	 */
	private function getTablesToRename() {
		global $TYPO3_DB;
		$tables = $TYPO3_DB->admin_get_tables();
		$tablesToRename = array();
		foreach ($tables as $tableName => $tableInfo) {
			if (strpos($tableName, 'tx_tcdirectmail') !== FALSE) {
				$tablesToRename[] = $tableName;
			}
		}
		return $tablesToRename;
	}

	/**
	 * Returns the tables to be renamed
	 *
	 * @global t3lib_DB $TYPO3_DB
	 * @return array
	 */
	private function getFieldsToRename($tableName) {
		global $TYPO3_DB;
		$fields = $TYPO3_DB->admin_get_fields($tableName);
		$fieldsToRename = array();
		foreach ($fields as $fieldName => $fieldInfo) {
			if (strpos($fieldName, 'tx_tcdirectmail') !== FALSE) {
				$fieldsToRename[] = $fieldName;
			}
		}
		return $fieldsToRename;
	}

	/**
	 * Returns the new name of the target
	 *
	 * @return string
	 */
	private function getTargetName($tableName) {
		$targetName = '';
		switch ($tableName) {
			case 'tx_tcdirectmail_bounceaccount':
				$targetName = 'tx_newsletter_domain_model_bounceaccount';
				break;
			case 'tx_tcdirectmail_clicklinks':
				$targetName = 'tx_newsletter_domain_model_clicklink';
				break;
				break;
			case 'tx_tcdirectmail_sentlog':
				$targetName = 'tx_newsletter_domain_model_email_queue';
				break;
				break;
			case 'tx_tcdirectmail_targets':
				$targetName = 'tx_newsletter_domain_model_recipientlist';
				break;
				break;
			case 'tx_tcdirectmail_lock':
				$targetName = 'tx_newsletter_domain_model_lock';
				break;
		}
		return $targetName;
	}

	/**
	 * This method checks whether it is necessary to display the UPDATE option at all
	 *
	 * @param	string	$what: What should be updated
	 */
	function access($what = 'all') {
		return TRUE;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/newsletter/class.ext_update.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/newsletter/class.ext_update.php']);
}
?>

<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Fabien Udriot <fabien.udriot@ecodev.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
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
*
* $Id$
***************************************************************/

/**
 * Classes used as ExtDirect's router
 *
 * @author	Fabien Udriot <fabien.udriot@ecodev.ch>
 * @package	TYPO3
 * @subpackage	tx_newsletter
 */
class tx_newsletter_remote {

	/**
	 * Extension Key name
	 *
	 * @var string
	 */
	protected $extensionKey = 'newsletter';
	
	/**
	 * Get / Post parameters
	 *
	 * @var array
	 */
	protected $parameters = array();

	/**
	 * Extension configuration
	 *
	 * @var array
	 */
	protected $configurations = array();

	/**
	 * Constructor
	 *
	 * @global Language $LANG;
	 */
	public function __construct() {
		global $LANG;
		$this->parameters = array_merge(t3lib_div::_GET(), t3lib_div::_POST());

		// Load language
		$LANG->includeLLFile('EXT:newsletter/Resources/Private/Language/locallang.xml');

		// Get extension configuration
		$this->configurations = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['newsletter']);
	}
	
	/**
	 * Get datasource's meta data
	 *
	 * @param array $fields: list of field
	 * @param string $tableName: the name of the table to look for
	 * @return array $metaData
	 */
	protected function getMetaData($fields, $tableName) {

		// ExtJS api: http://www.extjs.com/deploy/dev/docs/?class=Ext.data.JsonReader
//		metaData: {
//        // used by store to set its sortInfo
//        "sortInfo":{
//           "field": "name",
//           "direction": "ASC"
//        },
//        // paging data (if applicable)
//        "start": 0,
//        "limit": 2,
//        // custom property
//        "foo": "bar"
//    },
		$metaData['idProperty'] = 'uid';
		$metaData['root'] = 'records';
		$metaData['totalProperty'] = 'total';
		$metaData['successProperty'] = 'success';
		$metaData['fields'] = $this->getFieldMetaData($fields, $tableName);
		return $metaData;
	}

	/**
	 * Get MetaData for fields
	 *
	 * @global t3lib_DB $TYPO3_DB
	 * @param array $fields: list of field
	 * @param string $tableName: the name of the table to look for
	 * @return array $fieldsMetaData: list of metadata for the given $fields
	 */
	protected function getFieldMetaData($fields, $tableName) {
		global $TYPO3_DB;
		$fieldsInTable = $TYPO3_DB->admin_get_fields($tableName);

		foreach ($fields as $fieldName) {
			if ($fieldName == 'crdate' || $fieldName == 'tstamp' || $fieldName == 'begintime' || $fieldName == 'stoptime') {
				$fieldsMetaData[] = array('name' => $fieldName, 'type' => 'date', 'dateFormat' => 'timestamp');
			}
			elseif (isset($fieldsInTable[$fieldName])) {
				$fieldType = $fieldsInTable[$fieldName]['Type'];
				if (strpos($fieldType, 'int') !== FALSE) {
					$fieldsMetaData[] = array('name' => $fieldName, 'type' => 'int');
				}
				else { // means this is a string
					$fieldsMetaData[] = array('name' => $fieldName, 'type' => 'string');
				}
			}
		}
		return $fieldsMetaData;
	}

	
	/**
	 * This method returns the message's content
	 *
	 * @param	array			$PA: information related to the field
	 * @param	t3lib_tceform	$fobj: reference to calling TCEforms object
	 * @return	string	The HTML for the form field
	 */
	public function conateStrings($string1, $string2) {
		return $string1 . ' ' . $string2;
	}

	public function testMe($string1, $string2) {
		return $string1 . ' ' . $string2;
	}

	public function myMethod($string1, $string2) {
		return $string1 . ' ' . $string2;
	}

	public function getLogs() {
		$datasource['tid'] = 2;
		$datasource['action'] = 'Remote';
		$datasource['type'] = 'rpc';
		$datasource['method'] = 'getLogs';
		$datasource['data'] = array(
			array('source' => 'native', 'description' => 'Testin 1234'),

		);
		return $datasource;
	}

	/**
	 * Return a list of newsletter
	 *
	 * @global t3lib_DB $TYPO3_DB
	 * @return void
	 */
	public function getListOfNewsletter() {
		global $TYPO3_DB;

		// Defines list of fields
		$fields = array('uid', 'pid', 'begintime', 'stoptime');
		$tableName = 'tx_newsletter_domain_model_lock';

		$datasource['metaData'] = $this->getMetaData($fields, $tableName);
		$datasource['total'] = 0;
		$datasource['records'] = array();
		$datasource['success'] = FALSE;

		if (isset($this->parameters['pid']) && (int) $this->parameters['pid'] > 0) {
			$records = $TYPO3_DB->exec_SELECTgetRows(implode(',', $fields), $tableName, 'pid = ' . $this->parameters['pid'], $groupBy = '', $order = '', $limit = '');

	//		$request = $TYPO3_DB->SELECTquery(implode(',', $fields), $tableName, 'pid = ' . $this->parameters['pid'], $groupBy = '', $order = '', $limit = '');
	//		t3lib_div::debug($request, '$datasource');

			// Adds custom fields
			$datasource['metaData']['fields'][] = array('name' => 'number_of_recipients', 'type' => 'int');
			$datasource['metaData']['fields'][] = array('name' => 'newsletter_formatted', 'type' => 'string');
			foreach ($records as &$record) {
				$record['number_of_recipients'] = $this->getNumberOfRecipients($this->parameters['pid'], $record['begintime']);
				$record['newsletter_formatted'] = $this->formatNewsletter($record);
			}

			$datasource['records'] = $records;
			$datasource['total'] = $TYPO3_DB->exec_SELECTcountRows('uid', $tableName, 'pid = ' . $this->parameters['pid']);
			$datasource['success'] = TRUE;

			// Log results for debug purposes
//			t3lib_div::debug($datasource, '$datasource');
			t3lib_div::devLog('Ajax: result of method getListOfNewsletter()', $this->extensionKey, '0', $datasource);
			echo json_encode($datasource);
		}
		else {
			echo json_encode($datasource);
			throw new tx_devlog_exception('Missing parameter "pid" in URL', 1277746781);
		}
	}

	/**
	 * This method formats the newsletter list
	 *
	 * @param	array		$uid: primary key of the record
	 * @return	string		HTML to display
	 */
	protected function formatNewsletter($record) {
		global $LANG;

		$result = '';
		$result .= $LANG->getLL('title');
		$result .= ' ' . date($GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'], $record['begintime']);
		$result .= '@' . date($GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'], $record['begintime']);
		$result .= ' - ' . $record['number_of_recipients'];
		$result .= ' ' . $LANG->getLL('recipients');
		return $result;
	}

	/**
	 * Get the number of recipient for a newsletter
	 *
	 * @global t3lib_DB $TYPO3_DB
	 * @param int $pid: page id where newsletter is stored
	 * @param int $begintime: the time when the newsletter was sent
	 * @return int $fieldsMetaData: list of metadata for the given $fields
	 */
	protected function getNumberOfRecipients($pid, $begintime) {
		global $TYPO3_DB;

		$condition[] = 'pid = ' . $pid;
		$condition[] = 'begintime = ' . $begintime;

		$numberOfRecipients = $TYPO3_DB->exec_SELECTcountRows('uid', 'tx_newsletter_domain_model_email', implode(' AND ', $condition));

		return $numberOfRecipients;
	}

	/**
	 * Enter description here ...
	 * @formHandler
	 */
	public function getFormData()	{
	
		return "ads";
	}
}

?>
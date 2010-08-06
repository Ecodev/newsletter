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
***************************************************************/

/**
 * A repository for Statistics
 */
class Tx_Newsletter_Domain_Repository_StatisticRepository extends Tx_Extbase_Persistence_Repository {

	/**
	 * Returns all objects of this repository
	 *
	 * @param Tx_Newsletter_Domain_Model_Statistic $statistic
	 * @return array An array of objects, empty if no objects found
	 */
	public function findByPid(Tx_Newsletter_Domain_Model_Statistic $statistic) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
		$records = $query->matching($query->equals('pid', $statistic->getPid()))
				->execute();

		$this->additionalFields = array(
			array('name' => 'number_of_recipients', 'type' => 'int'),
			array('name' => 'newsletter_label_formatted', 'type' => 'string'),
		);
		
		// Adds custom fields
		foreach ($records as &$record) {
			$record['number_of_recipients'] = $this->getNumberOfRecipients($statistic->getPid(), $record['begintime']);
			$record['newsletter_label_formatted'] = '';
		}
		return $records;
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

		$numberOfRecipients = $TYPO3_DB->exec_SELECTcountRows('uid', 'tx_newsletter_sentlog', implode(' AND ', $condition));

		return $numberOfRecipients;
	}

	/**
	 * Get datasource's meta data
	 *
	 * @return array $metaData
	 */
	public function getMetaData() {
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
		$tableName = 'tx_newsletter_lock';
		$metaData['idProperty'] = 'uid';
		$metaData['root'] = 'records';
		$metaData['totalProperty'] = 'total';
		$metaData['successProperty'] = 'success';
		$metaData['fields'] = array_merge($this->getFieldMetaData($tableName), $this->additionalFields);
		return $metaData;
	}

	/**
	 * Get MetaData for fields
	 *
	 * @global t3lib_DB $TYPO3_DB
	 * @param string $tableName: the name of the table to look for
	 * @return array $fieldsMetaData: list of metadata for the given $fields
	 */
	protected function getFieldMetaData($tableName) {
		global $TYPO3_DB;
		$fieldsInTable = $TYPO3_DB->admin_get_fields($tableName);
		$fieldsMetaData = array();
		foreach ($fieldsInTable as $fieldName) {
			if ($fieldName == 'crdate' || $fieldName == 'tstamp' || $fieldName == 'begintime' || $fieldName == 'stoptime') {
				$fieldsMetaData[] = array('name' => $fieldName['Field'], 'type' => 'date', 'dateFormat' => 'timestamp');
			}
			$fieldType = $fieldName['Type'];
			if (strpos($fieldType, 'int') !== FALSE) {
				$fieldsMetaData[] = array('name' => $fieldName['Field'], 'type' => 'int');
			}
			else { // means this is a string
				$fieldsMetaData[] = array('name' => $fieldName['Field'], 'type' => 'string');
			}
		}
		return $fieldsMetaData;
	}

}
?>
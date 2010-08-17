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
 * A repository for Statistic
 */
class Tx_Newsletter_Domain_Repository_StatisticRepository extends Tx_Extbase_Persistence_Repository {


	/**
	 * Constructs a new Repository
	 *
	 */
	public function __construct() {
		parent::__construct();

		$this->additionalFields = array(
			array('name' => 'number_of_sent', 'type' => 'int'),
			array('name' => 'number_of_opened', 'type' => 'int'),
			array('name' => 'number_of_not_opened', 'type' => 'int'),
			array('name' => 'number_of_bounced', 'type' => 'int'),
			array('name' => 'number_of_recipients', 'type' => 'int'),
			array('name' => 'percent_of_opened', 'type' => 'int'),
			array('name' => 'percent_of_not_opened', 'type' => 'int'),
			array('name' => 'percent_of_bounced', 'type' => 'int'),
			array('name' => 'begintime_formatted', 'type' => 'string'),
			array('name' => 'stoptime_formatted', 'type' => 'string'),
			array('name' => 'statistic_label_formatted', 'type' => 'string'),
			array('name' => 'clicked_links'),
			array('name' => 'sent_emails'),
		);
	}

	/**
	 * Returns all objects of this repository
	 *
	 * @param int $uid
	 * @return array An array of objects, empty if no objects found
	 */
	public function findByUid($uid) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
		$records = $query->matching($query->equals('uid', $uid))
				->execute();

		$record = $records[0];
		$record['begintime_formatted'] = $record['stoptime_formatted'] = '';
		$record['number_of_recipients'] = $this->getNumberOfRecipients($record['pid'], $record['begintime']);
		$record['number_of_sent'] = $record['number_of_recipients'];
		$record['number_of_opened'] = $this->getNumberOfOpened($record['pid'], $record['begintime']);
		$record['number_of_not_opened'] = $record['number_of_sent'] - $record['number_of_opened'];
		$record['number_of_bounced'] = $this->getNumberOfBounce($record['pid'], $record['begintime']);
		$record['percent_of_opened'] = $record['percent_of_not_opened'] = $record['percent_of_bounced'] = 0;
		if ($record['number_of_sent'] != 0) {
			$record['percent_of_opened'] = round($record['number_of_opened'] * 100 / $record['number_of_sent'], 2);
			$record['percent_of_not_opened'] = round($record['number_of_not_opened'] * 100 / $record['number_of_sent'], 2);
			$record['percent_of_bounced'] = round($record['number_of_bounced'] * 100 / $record['number_of_sent'], 2);
		}
		$record['clicked_links'] = $this->getClickedLinks($record['begintime'], $record['number_of_opened']);
		$record['sent_emails'] = $this->getSentEmails($record['begintime']);
		return $record;
	}

	/**
	 * Get the number of sent email for a newsletter
	 * Temporarily relies on TYPO3_DB. The code must be refactored towards a query against a content repository
	 *
	 * @global t3lib_DB $TYPO3_DB
	 * @param int $begintime: the time when the newsletter was sent
	 * @param int $numberOfOpened
	 * @return int $fieldsMetaData: list of metadata for the given $fields
	 */
	protected function getSentEmails($begintime, $numberOfOpened) {
		global $TYPO3_DB;

		/* Get list of receivers */
		$pid = t3lib_div::_GET('pid');
	    $rs = $TYPO3_DB->exec_SELECTquery('*', 'pages', "uid = $pid");
	    $page = $TYPO3_DB->sql_fetch_assoc($rs);
		$targets = array_filter(explode(',',$page['tx_newsletter_real_target']));

	    foreach ($targets as $tid) {
			$recipients = $this->getRecipients($tid);
		}
		return $recipients;
	}

	/**
	 * This is the object factory, without init(), for all directmail targets.
	 *
	 * @param     integer     Uid of a tx_directmail_target from the database.
	 * @return    object      Of directmail_target type.
	 */
	protected function getRecipients($uid) {
		global $TYPO3_DB;

		$rs = $TYPO3_DB->sql_query("SELECT * FROM tx_newsletter_targets WHERE uid = $uid");
		$record = $TYPO3_DB->sql_fetch_assoc($rs);
		$repository = new Tx_Newsletter_Domain_Repository_RecipientRepository();
		return $repository->findAllByRecipientType($record);
	}

	/**
	 * Get the number of click links for a newsletter
	 * Temporarily relies on TYPO3_DB. The code must be refactored towards a query against a content repository
	 *
	 * @global t3lib_DB $TYPO3_DB
	 * @param int $begintime: the time when the newsletter was sent
	 * @param int $numberOfOpened
	 * @return int $fieldsMetaData: list of metadata for the given $fields
	 */
	protected function getClickedLinks($begintime, $numberOfOpened) {
		global $TYPO3_DB;

		$sql = "SELECT linkid AS link_id, SUM(opened) AS number_of_opened, MIN(url) AS url
                       FROM tx_newsletter_sentlog
                       INNER JOIN tx_newsletter_clicklinks ON sentlog = uid
                       WHERE begintime = $begintime
                       AND linktype = 'html'
                       AND opened = 1
                       GROUP BY 1
                       ORDER BY 1";


		$records = array();
		$res = $TYPO3_DB->sql_query($sql);
		while($row = $TYPO3_DB->sql_fetch_assoc($res)) {
			$row['percentage_of_opened'] = 0;
			if ($numberOfOpened != 0) {
				$row['percentage_of_opened'] = round($row['number_of_opened'] * 100 / $numberOfOpened, 2);
			}
			$row['total_number_of_opened'] = $numberOfOpened;
			$records[] = $row;
		}
		return $records;
	}

	/**
	 * Returns all objects of this repository
	 *
	 * @param Tx_Newsletter_Domain_Model_Statistic $statistic
	 * @return array An array of objects, empty if no objects found
	 */
	public function findAllByPid(Tx_Newsletter_Domain_Model_Statistic $statistic) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
		$records = $query->matching($query->equals('pid', $statistic->getPid()))
				->execute();
		
		// Adds custom fields
		foreach ($records as &$record) {
			$record['number_of_recipients'] = $this->getNumberOfRecipients($statistic->getPid(), $record['begintime']);
			$record['statistic_label_formatted'] = '';
		}
		return $records;
	}

	/**
	 * Get the number of recipient for a newsletter
	 * Temporarily relies on TYPO3_DB. The code must be refactored towards a query against a content repository
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

		return (int)$numberOfRecipients;
	}

	/**
	 * Get the number of opened email.
	 * Temporarily relies on TYPO3_DB. The code must be refactored towards a query against a content repository
	 *
	 * @global t3lib_DB $TYPO3_DB
	 * @param int $pid: page id where newsletter is stored
	 * @param int $begintime: the time when the newsletter was sent
	 * @return int $numberOfOpened
	 */
	protected function getNumberOfOpened($pid, $begintime) {
		global $TYPO3_DB;
		
		$sql = "SELECT COUNT(uid)
				FROM tx_newsletter_sentlog
				WHERE begintime = $begintime
				AND beenthere = 1
				AND pid = $pid";

		$rs = $TYPO3_DB->sql_query($sql);
		list($numberOfOpened) = $TYPO3_DB->sql_fetch_row($rs);
		return (int)$numberOfOpened;
	}

	/**
	 * Get the number of bounce email.
	 * Temporarily relies on TYPO3_DB. The code must be refactored towards a query against a content repository
	 *
	 * @global t3lib_DB $TYPO3_DB
	 * @param int $pid: page id where newsletter is stored
	 * @param int $begintime: the time when the newsletter was sent
	 * @return int $numberOfBounce
	 */
	protected function getNumberOfBounce($pid, $begintime) {
		global $TYPO3_DB;

		$sql = "SELECT SUM(bounced)
				FROM tx_newsletter_sentlog
				WHERE begintime = $begintime
				AND pid = $pid";

		$rs = $TYPO3_DB->sql_query($sql);
		list($numberOfBounce) = $TYPO3_DB->sql_fetch_row($rs);
		return (int)$numberOfBounce;
	}

	/**
	 * Get datasource's meta data for statistic.
	 * The method will return an array containing information for a JsonStore
	 * ExtJS api: http://www.extjs.com/deploy/dev/docs/?class=Ext.data.JsonReader
	 *
	 * @return array $metaData
	 */
	public function getMetaDataForMultipleRecords() {
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
	 * Get datasource's meta data for statistic.
	 * The method will return an array containing information for a JsonStore
	 * ExtJS api: http://www.extjs.com/deploy/dev/docs/?class=Ext.data.JsonReader
	 *
	 * @return array $metaData
	 */
	public function getMetaDataForSingleRecord() {
		$tableName = 'tx_newsletter_lock';
		$metaData['idProperty'] = 'uid';
		$metaData['root'] = 'data';
		$metaData['totalProperty'] = 'total';
		$metaData['successProperty'] = 'success';
		$metaData['fields'] = array_merge($this->getFieldMetaData($tableName), $this->additionalFields);
		return $metaData;
	}

	/**
	 * Get MetaData for fields.
	 * Implementation: queries the database and retrieve the fields' type.
	 * This implementation is temporary and should go towards quering the model
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
			if ($fieldName['Field'] == 'crdate' ||
					$fieldName['Field'] == 'tstamp' ||
					$fieldName['Field'] == 'begintime' ||
					$fieldName['Field'] == 'stoptime') {
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
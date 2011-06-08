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
class Tx_Newsletter_Domain_Repository_StatisticRepository extends Tx_Newsletter_Domain_Repository_AbstractRepository {


	/**
	 * Constructs a new Repository
	 *
	 */
	public function __construct() {
		parent::__construct();

		$this->additionalFields = array(
			array('name' => 'number_of_sent', 'type' => 'int'),
			array('name' => 'number_of_not_sent', 'type' => 'int'),
			array('name' => 'number_of_opened', 'type' => 'int'),
			array('name' => 'number_of_bounced', 'type' => 'int'),
			array('name' => 'number_of_recipients', 'type' => 'int'),
			array('name' => 'percent_of_opened', 'type' => 'int'),
			array('name' => 'percent_of_not_opened', 'type' => 'int'),
			array('name' => 'percent_of_bounced', 'type' => 'int'),
			array('name' => 'begin_time_formatted', 'type' => 'string'),
			array('name' => 'end_time_formatted', 'type' => 'string'),
			array('name' => 'statistic_label_formatted', 'type' => 'string'),
			array('name' => 'clicked_links'),
			array('name' => 'sent_emails'),
		);
	}

	/**
	 * Returns the number of statistics for a give pid
	 *
	 * @param int $pid the page uid
	 * @return boolean
	 */
	public function countStatistics($pid) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
		$result = $query->statement("SELECT COUNT(*) AS count FROM tx_newsletter_domain_model_newsletter WHERE pid = $pid")
				->execute();
		$numberOfRecords = $result[0]['count'];
		
		return $numberOfRecords;
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
		$records = $query->statement("SELECT * FROM tx_newsletter_domain_model_newsletter WHERE uid = $uid")
				->execute();
		$record = $records[0];
		$record['begin_time_formatted'] = $record['end_time_formatted'] = '';
		$record['number_of_recipients'] = $this->getNumberOfRecipients($record['uid']);
		$record['number_of_sent'] = $this->getNumberOfSent($record['uid']);
		$record['number_of_not_sent'] = $this->getNumberOfNotSent($record['uid']);
		$record['number_of_opened'] = $this->getNumberOfOpened($record['uid']);
		$record['number_of_bounced'] = $this->getNumberOfBounce($record['uid']);
		
		$record['percent_of_opened'] = $record['percent_of_not_opened'] = $record['percent_of_bounced'] = 0;
		if ($record['number_of_recipients'] != 0) {
			$record['percent_of_opened'] = round($record['number_of_opened'] * 100 / $record['number_of_recipients'], 2);
			$record['percent_of_not_opened'] = round($record['number_of_not_opened'] * 100 / $record['number_of_recipients'], 2);
			$record['percent_of_bounced'] = round($record['number_of_bounced'] * 100 / $record['number_of_recipients'], 2);
		}
		$record['clicked_links'] = $this->getClickedLinks($record['uid'], $record['number_of_recipients']);
		$record['sent_emails'] = $this->getSentEmails($record['uid']);
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
	protected function getSentEmails($uidNewsletter) {
		global $TYPO3_DB;

		$sql = "SELECT uid, recipient_address, end_time, opened, bounced
                       FROM tx_newsletter_domain_model_email
                       WHERE newsletter = $uidNewsletter";

		$records = array();
		$res = $TYPO3_DB->sql_query($sql);
		while($row = $TYPO3_DB->sql_fetch_assoc($res)) {
			$row['preview'] = md5($row['uid'] . $row['recipient_address']);
			$records[] = $row;
		}
		
		return $records;
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
	protected function getClickedLinks($uidNewsletter, $numberOfRecipients) {
		global $TYPO3_DB;

		$sql = "SELECT uid, opened_count, url
                       FROM tx_newsletter_domain_model_link
                       WHERE newsletter = $uidNewsletter";


		$records = array();
		$res = $TYPO3_DB->sql_query($sql);
		while($row = $TYPO3_DB->sql_fetch_assoc($res)) {
			$row['percentage_of_opened'] = 0;
			if ($numberOfRecipients != 0) {
				$row['percentage_of_opened'] = round($row['opened_count'] * 100 / $numberOfRecipients, 2);
			}
			$row['number_of_recipients'] = $numberOfRecipients;
			$records[] = $row;
		}
		return $records;
	}

	/**
	 * Returns all objects of this repository
	 *
	 * @param int $pid
	 * @return array An array of objects, empty if no objects found
	 */
	public function findAllByPid($pid) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
		$records = $query->statement("SELECT * FROM tx_newsletter_domain_model_newsletter WHERE pid = $pid")
				->execute();
		
		// Adds custom fields
		foreach ($records as &$record) {
			$record['number_of_recipients'] = $this->getNumberOfRecipients($record['uid']);
			$record['statistic_label_formatted'] = '';
		}
		return $records;
	}

	/**
	 * Get the number of recipient for a newsletter
	 * @global t3lib_DB $TYPO3_DB
	 * @param int $uidNewsletter
	 * @return int count of recipients
	 */
	protected function getNumberOfRecipients($uidNewsletter) {
		global $TYPO3_DB;
		
		$numberOfRecipients = $TYPO3_DB->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'newsletter = ' . $uidNewsletter);

		return (int)$numberOfRecipients;
	}
	
	/**
	 * Get the number of not yet sent email for a newsletter
	 * @global t3lib_DB $TYPO3_DB
	 * @param int $uidNewsletter
	 * @return int count of recipients
	 */
	protected function getNumberOfNotSent($uidNewsletter) {
		global $TYPO3_DB;
		
		$numberOfNotSent = $TYPO3_DB->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'end_time = 0 AND newsletter = ' . $uidNewsletter);

		return (int)$numberOfNotSent;
	}
	
	/**
	 * Get the number of sent email for a newsletter
	 * @global t3lib_DB $TYPO3_DB
	 * @param int $uidNewsletter
	 * @return int count of recipients
	 */
	protected function getNumberOfSent($uidNewsletter) {
		global $TYPO3_DB;
		
		$numberOfSent = $TYPO3_DB->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'end_time != 0 AND opened = 0 AND bounced = 0 AND newsletter = ' . $uidNewsletter);

		return (int)$numberOfSent;
	}

	/**
	 * Get the number of opened email.
	 * Temporarily relies on TYPO3_DB. The code must be refactored towards a query against a content repository
	 * @global t3lib_DB $TYPO3_DB
	 * @param int $uidNewsletter
	 * @return int count of opened emails
	 */
	protected function getNumberOfOpened($uidNewsletter) {
		global $TYPO3_DB;
		
		$numberOfOpened = $TYPO3_DB->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'opened = 1 AND bounced = 0 AND newsletter = ' . $uidNewsletter);
		
		return (int)$numberOfOpened;
	}

	/**
	 * Get the number of bounce email.
	 * Temporarily relies on TYPO3_DB. The code must be refactored towards a query against a content repository
	 *
	 * @global t3lib_DB $TYPO3_DB
	 * @param int $uidNewsletter
	 * @return int $numberOfBounce
	 */
	protected function getNumberOfBounce($uidNewsletter) {
		global $TYPO3_DB;

		$numberOfBounce = $TYPO3_DB->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'bounced = 1 AND newsletter = ' . $uidNewsletter);
		
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
		
		$tableName = 'tx_newsletter_domain_model_newsletter';
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
		$tableName = 'tx_newsletter_domain_model_newsletter';
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
		foreach ($fieldsInTable as $field) {
			$fieldName = $field['Field'];
			$fieldType = $field['Type'];
			
			if (preg_match('/_time$/', $fieldName) ||
					$fieldName == 'crdate' ||
					$fieldName == 'tstamp') {
				$fieldsMetaData[] = array('name' => $fieldName, 'type' => 'date', 'dateFormat' => 'timestamp');
			}
			elseif (strpos($fieldType, 'int') !== FALSE) {
				$fieldsMetaData[] = array('name' => $fieldName, 'type' => 'int');
			}
			else { // means this is a string
				$fieldsMetaData[] = array('name' => $fieldName, 'type' => 'string');
			}
		}
		return $fieldsMetaData;
	}

}
?>
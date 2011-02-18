<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 3 of the License, or
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
 * RecipientList
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Tx_Newsletter_Domain_Model_RecipientList extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * title
	 *
	 * @var string $title
	 */
	protected $title;

	/**
	 * plainOnly
	 *
	 * @var boolean $plainOnly
	 */
	protected $plainOnly;

	/**
	 * lang
	 *
	 * @var string $lang
	 */
	protected $lang;

	/**
	 * type
	 *
	 * @var string $type
	 */
	protected $type;

	/**
	 * beUsers
	 *
	 * @var string $beUsers
	 */
	protected $beUsers;

	/**
	 * feGroups
	 *
	 * @var string $feGroups
	 */
	protected $feGroups;

	/**
	 * fePages
	 *
	 * @var string $fePages
	 */
	protected $fePages;

	/**
	 * ttAddress
	 *
	 * @var string $ttAddress
	 */
	protected $ttAddress;

	/**
	 * csvUrl
	 *
	 * @var string $csvUrl
	 */
	protected $csvUrl;

	/**
	 * csvSeparator
	 *
	 * @var string $csvSeparator
	 */
	protected $csvSeparator;

	/**
	 * csvFields
	 *
	 * @var string $csvFields
	 */
	protected $csvFields;

	/**
	 * csvFilename
	 *
	 * @var string $csvFilename
	 */
	protected $csvFilename;

	/**
	 * csvValues
	 *
	 * @var string $csvValues
	 */
	protected $csvValues;

	/**
	 * sql
	 *
	 * @var string $sql
	 */
	protected $sql;

	/**
	 * htmlFile
	 *
	 * @var string $htmlFile
	 */
	protected $htmlFile;

	/**
	 * htmlFetchType
	 *
	 * @var string $htmlFetchType
	 */
	protected $htmlFetchType;

	/**
	 * calculatedRecipients
	 *
	 * @var string $calculatedRecipients
	 */
	protected $calculatedRecipients;

	/**
	 * confirmedRecipients
	 *
	 * @var string $confirmedRecipients
	 */
	protected $confirmedRecipients;

	/**
	 * Setter for title
	 *
	 * @param string $title title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Getter for title
	 *
	 * @return string title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Setter for plainOnly
	 *
	 * @param boolean $plainOnly plainOnly
	 * @return void
	 */
	public function setPlainOnly($plainOnly) {
		$this->plainOnly = $plainOnly;
	}

	/**
	 * Getter for plainOnly
	 *
	 * @return boolean plainOnly
	 */
	public function getPlainOnly() {
		return $this->plainOnly;
	}

	/**
	 * Returns the state of plainOnly
	 *
	 * @return boolean the state of plainOnly
	 */
	public function isPlainOnly() {
		return $this->getPlainOnly();
	}

	/**
	 * Setter for lang
	 *
	 * @param string $lang lang
	 * @return void
	 */
	public function setLang($lang) {
		$this->lang = $lang;
	}

	/**
	 * Getter for lang
	 *
	 * @return string lang
	 */
	public function getLang() {
		return $this->lang;
	}

	/**
	 * Setter for type
	 *
	 * @param string $type type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Getter for type
	 *
	 * @return string type
	 */
	public function getType() {
		return $this->type;
	}

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
	 * Setter for feGroups
	 *
	 * @param string $feGroups feGroups
	 * @return void
	 */
	public function setFeGroups($feGroups) {
		$this->feGroups = $feGroups;
	}

	/**
	 * Getter for feGroups
	 *
	 * @return string feGroups
	 */
	public function getFeGroups() {
		return $this->feGroups;
	}

	/**
	 * Setter for fePages
	 *
	 * @param string $fePages fePages
	 * @return void
	 */
	public function setFePages($fePages) {
		$this->fePages = $fePages;
	}

	/**
	 * Getter for fePages
	 *
	 * @return string fePages
	 */
	public function getFePages() {
		return $this->fePages;
	}

	/**
	 * Setter for ttAddress
	 *
	 * @param string $ttAddress ttAddress
	 * @return void
	 */
	public function setTtAddress($ttAddress) {
		$this->ttAddress = $ttAddress;
	}

	/**
	 * Getter for ttAddress
	 *
	 * @return string ttAddress
	 */
	public function getTtAddress() {
		return $this->ttAddress;
	}

	/**
	 * Setter for csvUrl
	 *
	 * @param string $csvUrl csvUrl
	 * @return void
	 */
	public function setCsvUrl($csvUrl) {
		$this->csvUrl = $csvUrl;
	}

	/**
	 * Getter for csvUrl
	 *
	 * @return string csvUrl
	 */
	public function getCsvUrl() {
		return $this->csvUrl;
	}

	/**
	 * Setter for csvSeparator
	 *
	 * @param string $csvSeparator csvSeparator
	 * @return void
	 */
	public function setCsvSeparator($csvSeparator) {
		$this->csvSeparator = $csvSeparator;
	}

	/**
	 * Getter for csvSeparator
	 *
	 * @return string csvSeparator
	 */
	public function getCsvSeparator() {
		return $this->csvSeparator;
	}

	/**
	 * Setter for csvFields
	 *
	 * @param string $csvFields csvFields
	 * @return void
	 */
	public function setCsvFields($csvFields) {
		$this->csvFields = $csvFields;
	}

	/**
	 * Getter for csvFields
	 *
	 * @return string csvFields
	 */
	public function getCsvFields() {
		return $this->csvFields;
	}

	/**
	 * Setter for csvFilename
	 *
	 * @param string $csvFilename csvFilename
	 * @return void
	 */
	public function setCsvFilename($csvFilename) {
		$this->csvFilename = $csvFilename;
	}

	/**
	 * Getter for csvFilename
	 *
	 * @return string csvFilename
	 */
	public function getCsvFilename() {
		return $this->csvFilename;
	}

	/**
	 * Setter for csvValues
	 *
	 * @param string $csvValues csvValues
	 * @return void
	 */
	public function setCsvValues($csvValues) {
		$this->csvValues = $csvValues;
	}

	/**
	 * Getter for csvValues
	 *
	 * @return string csvValues
	 */
	public function getCsvValues() {
		return $this->csvValues;
	}

	/**
	 * Setter for sql
	 *
	 * @param string $sql sql
	 * @return void
	 */
	public function setSql($sql) {
		$this->sql = $sql;
	}

	/**
	 * Getter for sql
	 *
	 * @return string sql
	 */
	public function getSql() {
		return $this->sql;
	}

	/**
	 * Setter for htmlFile
	 *
	 * @param string $htmlFile htmlFile
	 * @return void
	 */
	public function setHtmlFile($htmlFile) {
		$this->htmlFile = $htmlFile;
	}

	/**
	 * Getter for htmlFile
	 *
	 * @return string htmlFile
	 */
	public function getHtmlFile() {
		return $this->htmlFile;
	}

	/**
	 * Setter for htmlFetchType
	 *
	 * @param string $htmlFetchType htmlFetchType
	 * @return void
	 */
	public function setHtmlFetchType($htmlFetchType) {
		$this->htmlFetchType = $htmlFetchType;
	}

	/**
	 * Getter for htmlFetchType
	 *
	 * @return string htmlFetchType
	 */
	public function getHtmlFetchType() {
		return $this->htmlFetchType;
	}

	/**
	 * Setter for calculatedRecipients
	 *
	 * @param string $calculatedRecipients calculatedRecipients
	 * @return void
	 */
	public function setCalculatedRecipients($calculatedRecipients) {
		$this->calculatedRecipients = $calculatedRecipients;
	}

	/**
	 * Getter for calculatedRecipients
	 *
	 * @return string calculatedRecipients
	 */
	public function getCalculatedRecipients() {
		return $this->calculatedRecipients;
	}

	/**
	 * Setter for confirmedRecipients
	 *
	 * @param string $confirmedRecipients confirmedRecipients
	 * @return void
	 */
	public function setConfirmedRecipients($confirmedRecipients) {
		$this->confirmedRecipients = $confirmedRecipients;
	}

	/**
	 * Getter for confirmedRecipients
	 *
	 * @return string confirmedRecipients
	 */
	public function getConfirmedRecipients() {
		return $this->confirmedRecipients;
	}

}
?>
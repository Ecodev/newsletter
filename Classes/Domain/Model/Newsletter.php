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
 * Newsletter represents a page to be sent to a specific time to several recipients.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Tx_Newsletter_Domain_Model_Newsletter extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * When the newsletter will start sending emails
	 *
	 * @var integer $plannedTime
	 * @validate NotEmpty
	 */
	protected $plannedTime;

	/**
	 * beginTime
	 *
	 * @var string $beginTime
	 */
	protected $beginTime;

	/**
	 * endTime
	 *
	 * @var string $endTime
	 */
	protected $endTime;

	/**
	 * 0-7 values to indicates when this newsletter will repeat
	 *
	 * @var integer $repeat
	 */
	protected $repeat;

	/**
	 * Tool used to convert to plain text
	 *
	 * @var string $plainConverter
	 */
	protected $plainConverter;

	/**
	 * Whether this newsletter is for test purpose. If it is it will be ignored in statistics
	 *
	 * @var boolean $isTest
	 * @validate NotEmpty
	 */
	protected $isTest;

	/**
	 * List of files to be attached (comma separated list
	 *
	 * @var string $attachments
	 */
	protected $attachments;

	/**
	 * The name of the newsletter sender
	 *
	 * @var string $senderName
	 * @validate NotEmpty
	 */
	protected $senderName;

	/**
	 * The email of the newsletter sender
	 *
	 * @var string $senderEmail
	 * @validate NotEmpty
	 */
	protected $senderEmail;

	/**
	 * injectOpenSpy
	 *
	 * @var boolean $injectOpenSpy
	 */
	protected $injectOpenSpy;

	/**
	 * injectLinksSpy
	 *
	 * @var boolean $injectLinksSpy
	 */
	protected $injectLinksSpy;

	/**
	 * bounceAccount
	 *
	 * @var Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount
	 */
	protected $bounceAccount;

	/**
	 * recipientList
	 *
	 * @var Tx_Newsletter_Domain_Model_RecipientList $recipientList
	 */
	protected $recipientList;

	/**
	 * Setter for plannedTime
	 *
	 * @param integer $plannedTime When the newsletter will start sending emails
	 * @return void
	 */
	public function setPlannedTime($plannedTime) {
		$this->plannedTime = $plannedTime;
	}

	/**
	 * Getter for plannedTime
	 *
	 * @return integer When the newsletter will start sending emails
	 */
	public function getPlannedTime() {
		return $this->plannedTime;
	}

	/**
	 * Setter for beginTime
	 *
	 * @param string $beginTime beginTime
	 * @return void
	 */
	public function setBeginTime($beginTime) {
		$this->beginTime = $beginTime;
	}

	/**
	 * Getter for beginTime
	 *
	 * @return string beginTime
	 */
	public function getBeginTime() {
		return $this->beginTime;
	}

	/**
	 * Setter for endTime
	 *
	 * @param string $endTime endTime
	 * @return void
	 */
	public function setEndTime($endTime) {
		$this->endTime = $endTime;
	}

	/**
	 * Getter for endTime
	 *
	 * @return string endTime
	 */
	public function getEndTime() {
		return $this->endTime;
	}

	/**
	 * Setter for repeat
	 *
	 * @param integer $repeat 0-7 values to indicates when this newsletter will repeat
	 * @return void
	 */
	public function setRepeat($repeat) {
		$this->repeat = $repeat;
	}

	/**
	 * Getter for repeat
	 *
	 * @return integer 0-7 values to indicates when this newsletter will repeat
	 */
	public function getRepeat() {
		return $this->repeat;
	}

	/**
	 * Setter for plainConverter
	 *
	 * @param string $plainConverter Tool used to convert to plain text
	 * @return void
	 */
	public function setPlainConverter($plainConverter) {
		$this->plainConverter = $plainConverter;
	}

	/**
	 * Getter for plainConverter
	 *
	 * @return string Tool used to convert to plain text
	 */
	public function getPlainConverter() {
		return $this->plainConverter;
	}

	/**
	 * Setter for isTest
	 *
	 * @param boolean $isTest Whether this newsletter is for test purpose. If it is it will be ignored in statistics
	 * @return void
	 */
	public function setIsTest($isTest) {
		$this->isTest = $isTest;
	}

	/**
	 * Getter for isTest
	 *
	 * @return boolean Whether this newsletter is for test purpose. If it is it will be ignored in statistics
	 */
	public function getIsTest() {
		return $this->isTest;
	}

	/**
	 * Returns the state of isTest
	 *
	 * @return boolean the state of isTest
	 */
	public function isIsTest() {
		return $this->getIsTest();
	}

	/**
	 * Setter for attachments
	 *
	 * @param string $attachments List of files to be attached (comma separated list
	 * @return void
	 */
	public function setAttachments($attachments) {
		$this->attachments = $attachments;
	}

	/**
	 * Getter for attachments
	 *
	 * @return string List of files to be attached (comma separated list
	 */
	public function getAttachments() {
		return $this->attachments;
	}

	/**
	 * Setter for senderName
	 *
	 * @param string $senderName The name of the newsletter sender
	 * @return void
	 */
	public function setSenderName($senderName) {
		$this->senderName = $senderName;
	}

	/**
	 * Getter for senderName
	 *
	 * @return string The name of the newsletter sender
	 */
	public function getSenderName() {
		return $this->senderName;
	}

	/**
	 * Setter for senderEmail
	 *
	 * @param string $senderEmail The email of the newsletter sender
	 * @return void
	 */
	public function setSenderEmail($senderEmail) {
		$this->senderEmail = $senderEmail;
	}

	/**
	 * Getter for senderEmail
	 *
	 * @return string The email of the newsletter sender
	 */
	public function getSenderEmail() {
		return $this->senderEmail;
	}

	/**
	 * Setter for injectOpenSpy
	 *
	 * @param boolean $injectOpenSpy injectOpenSpy
	 * @return void
	 */
	public function setInjectOpenSpy($injectOpenSpy) {
		$this->injectOpenSpy = $injectOpenSpy;
	}

	/**
	 * Getter for injectOpenSpy
	 *
	 * @return boolean injectOpenSpy
	 */
	public function getInjectOpenSpy() {
		return $this->injectOpenSpy;
	}

	/**
	 * Returns the state of injectOpenSpy
	 *
	 * @return boolean the state of injectOpenSpy
	 */
	public function isInjectOpenSpy() {
		return $this->getInjectOpenSpy();
	}

	/**
	 * Setter for injectLinksSpy
	 *
	 * @param boolean $injectLinksSpy injectLinksSpy
	 * @return void
	 */
	public function setInjectLinksSpy($injectLinksSpy) {
		$this->injectLinksSpy = $injectLinksSpy;
	}

	/**
	 * Getter for injectLinksSpy
	 *
	 * @return boolean injectLinksSpy
	 */
	public function getInjectLinksSpy() {
		return $this->injectLinksSpy;
	}

	/**
	 * Returns the state of injectLinksSpy
	 *
	 * @return boolean the state of injectLinksSpy
	 */
	public function isInjectLinksSpy() {
		return $this->getInjectLinksSpy();
	}

	/**
	 * Setter for bounceAccount
	 *
	 * @param Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount bounceAccount
	 * @return void
	 */
	public function setBounceAccount(Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount) {
		$this->bounceAccount = $bounceAccount;
	}

	/**
	 * Getter for bounceAccount
	 *
	 * @return Tx_Newsletter_Domain_Model_BounceAccount bounceAccount
	 */
	public function getBounceAccount() {
		return $this->bounceAccount;
	}

	/**
	 * Setter for recipientList
	 *
	 * @param Tx_Newsletter_Domain_Model_RecipientList $recipientList recipientList
	 * @return void
	 */
	public function setRecipientList(Tx_Newsletter_Domain_Model_RecipientList $recipientList) {
		$this->recipientList = $recipientList;
	}

	/**
	 * Getter for recipientList
	 *
	 * @return Tx_Newsletter_Domain_Model_RecipientList recipientList
	 */
	public function getRecipientList() {
		return $this->recipientList;
	}

}
?>
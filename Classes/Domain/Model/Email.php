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
 * Email
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Tx_Newsletter_Domain_Model_Email extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * beginTime
	 *
	 * @var DateTime $beginTime
	 */
	protected $beginTime;

	/**
	 * endTime
	 *
	 * @var DateTime $endTime
	 */
	protected $endTime;

	/**
	 * recipientAddress
	 *
	 * @var string $recipientAddress
	 * @validate NotEmpty
	 */
	protected $recipientAddress;

	/**
	 * recipientData
	 *
	 * @var string $recipientData
	 */
	protected $recipientData;

	/**
	 * openeTime
	 *
	 * @var DateTime $openTime
	 */
	protected $openTime;

	/**
	 * bounceTime
	 *
	 * @var DateTime $bounceTime
	 */
	protected $bounceTime;

	/**
	 * newsletter
	 * @lazy
	 * @var Tx_Newsletter_Domain_Model_Newsletter $newsletter
	 */
	protected $newsletter;

	/**
	 * Whether the recipient of this email requested to unsubscribe.
	 *
	 * @var boolean $unsubscribed
	 * @validate NotEmpty
	 */
	protected $unsubscribed;

	/**
	 * Setter for beginTime
	 *
	 * @param DateTime $beginTime beginTime
	 * @return void
	 */
	public function setBeginTime(DateTime $beginTime) {
		$this->beginTime = $beginTime;
	}

	/**
	 * Getter for beginTime
	 *
	 * @return DateTime beginTime
	 */
	public function getBeginTime() {
		return $this->beginTime;
	}

	/**
	 * Setter for endTime
	 *
	 * @param DateTime $endTime endTime
	 * @return void
	 */
	public function setEndTime(DateTime $endTime) {
		$this->endTime = $endTime;
	}

	/**
	 * Getter for endTime
	 *
	 * @return DateTime endTime
	 */
	public function getEndTime() {
		return $this->endTime;
	}

	/**
	 * Setter for recipientAddress
	 *
	 * @param string $recipientAddress recipientAddress
	 * @return void
	 */
	public function setRecipientAddress($recipientAddress) {
		$this->recipientAddress = $recipientAddress;
	}

	/**
	 * Getter for recipientAddress
	 *
	 * @return string recipientAddress
	 */
	public function getRecipientAddress() {
		return $this->recipientAddress;
	}

	/**
	 * Setter for recipientData
	 *
	 * @param array $recipientData recipientData
	 * @return void
	 */
	public function setRecipientData(array $recipientData) {
		$this->recipientData = serialize($recipientData);
	}

	/**
	 * Getter for recipientData
	 *
	 * @return array recipientData
	 */
	public function getRecipientData() {
		return unserialize($this->recipientData);
	}

	/**
	 * Getter for authCode
	 *
	 * @return string authCode
	 */
	public function getAuthCode() {
		return md5($this->uid . $this->getRecipientAddress());
	}

	/**
	 * Setter for openTime
	 *
	 * @param DateTime $openTime openTime
	 * @return void
	 */
	public function setOpenTime(DateTime $openTime) {
		$this->openTime = $openTime;
	}

	/**
	 * Getter for openTime
	 *
	 * @return DateTime openTime
	 */
	public function getOpenTime() {
		return $this->openTime;
	}

	/**
	 * Returns the state of opened
	 *
	 * @return boolean the state of opened
	 */
	public function isOpened() {
		return $this->getOpenTime() > 0;
	}

	/**
	 * Setter for bounceTime
	 *
	 * @param DateTime $bounceTime bounceTime
	 * @return void
	 */
	public function setBounceTime(DateTime $bounceTime) {
		$this->bounceTime = $bounceTime;
	}

	/**
	 * Getter for bounceTime
	 *
	 * @return DateTime bounceTime
	 */
	public function getBounceTime() {
		return $this->bounceTime;
	}

	/**
	 * Returns the state of bounced
	 *
	 * @return boolean the state of bounced
	 */
	public function isBounced() {
		return $this->getBounceTime() > 0;
	}

	/**
	 * Setter for newsletter
	 *
	 * @param Tx_Newsletter_Domain_Model_Newsletter $newsletter newsletter
	 * @return void
	 */
	public function setNewsletter(Tx_Newsletter_Domain_Model_Newsletter $newsletter) {
		$this->newsletter = $newsletter;
	}

	/**
	 * Getter for newsletter
	 *
	 * @return Tx_Newsletter_Domain_Model_Newsletter newsletter
	 */
	public function getNewsletter() {
		return $this->newsletter;
	}

	/**
	 * Setter for unsubscribed
	 *
	 * @param boolean $unsubscribed Whether the recipient of this email requested to unsubscribe.
	 * @return void
	 */
	public function setUnsubscribed($unsubscribed) {
		$this->unsubscribed = $unsubscribed;
	}

	/**
	 * Getter for unsubscribed
	 *
	 * @return boolean Whether the recipient of this email requested to unsubscribe.
	 */
	public function getUnsubscribed() {
		return $this->unsubscribed;
	}
}

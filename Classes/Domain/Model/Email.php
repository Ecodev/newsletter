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
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Tx_Newsletter_Domain_Model_Email extends Tx_Extbase_DomainObject_AbstractEntity {

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
	 * opened
	 *
	 * @var boolean $opened
	 */
	protected $opened;

	/**
	 * bounced
	 *
	 * @var boolean $bounced
	 */
	protected $bounced;

	/**
	 * host
	 *
	 * @var string $host
	 */
	protected $host;

	/**
	 * newsletter
	 *
	 * @var Tx_Newsletter_Domain_Model_Newsletter $newsletter
	 */
	protected $newsletter;

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
	 * Setter for opened
	 *
	 * @param boolean $opened opened
	 * @return void
	 */
	public function setOpened($opened) {
		$this->opened = $opened;
	}

	/**
	 * Getter for opened
	 *
	 * @return boolean opened
	 */
	public function getOpened() {
		return $this->opened;
	}

	/**
	 * Returns the state of opened
	 *
	 * @return boolean the state of opened
	 */
	public function isOpened() {
		return $this->getOpened();
	}

	/**
	 * Setter for bounced
	 *
	 * @param boolean $bounced bounced
	 * @return void
	 */
	public function setBounced($bounced) {
		$this->bounced = $bounced;
	}

	/**
	 * Getter for bounced
	 *
	 * @return boolean bounced
	 */
	public function getBounced() {
		return $this->bounced;
	}

	/**
	 * Returns the state of bounced
	 *
	 * @return boolean the state of bounced
	 */
	public function isBounced() {
		return $this->getBounced();
	}

	/**
	 * Setter for host
	 *
	 * @param string $host host
	 * @return void
	 */
	public function setHost($host) {
		$this->host = $host;
	}

	/**
	 * Getter for host
	 *
	 * @return string host
	 */
	public function getHost() {
		return $this->host;
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

}
?>
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
 * Link
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Tx_Newsletter_Domain_Model_Link extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * type
	 *
	 * @var string $type
	 * @validate NotEmpty
	 */
	protected $type;

	/**
	 * url
	 *
	 * @var string $url
	 */
	protected $url;

	/**
	 * opened
	 *
	 * @var boolean $opened
	 */
	protected $opened;

	/**
	 * email
	 *
	 * @var Tx_Newsletter_Domain_Model_Email $email
	 */
	protected $email;

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
	 * Setter for url
	 *
	 * @param string $url url
	 * @return void
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * Getter for url
	 *
	 * @return string url
	 */
	public function getUrl() {
		return $this->url;
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
	 * Setter for email
	 *
	 * @param Tx_Newsletter_Domain_Model_Email $email email
	 * @return void
	 */
	public function setEmail(Tx_Newsletter_Domain_Model_Email $email) {
		$this->email = $email;
	}

	/**
	 * Getter for email
	 *
	 * @return Tx_Newsletter_Domain_Model_Email email
	 */
	public function getEmail() {
		return $this->email;
	}

}
?>
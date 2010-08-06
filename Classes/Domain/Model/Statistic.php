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
 * A statistic.
 *
 * @author      Fabien Udriot <fabien.udriot@ecodev.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_Newsletter_Domain_Model_Statistic extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * The statistic's uid
	 *
	 * @var string
	 */
//	protected $uid = '';

	/**
	 * The statistic's uid
	 *
	 * @var int
	 */
	protected $pid = '';

	/**
	 * The statistic's begintime
	 *
	 * @var int
	 */
	protected $begintime = '';
//
//	/**
//	 * Sets this statistic's uid.
//	 *
//	 * @param string $uid The statistic's uid
//	 * @return void
//	 */
//	public function setUid($uid) {
//		$this->uid = $uid;
//	}
//
//	/**
//	 * Returns the statistic's uid.
//	 *
//	 * @return string The statistic's uid
//	 */
//	public function getUid() {
//		return $this->uid;
//	}

	/**
	 * Constructs a new Blog
	 *
	 */
	public function __construct() {
		#$this->posts = new Tx_Extbase_Persistence_ObjectStorage();
	}


	/**
	 * Sets this statistic's pid.
	 *
	 * @param string $pid The statistic's pid
	 * @return void
	 */
	public function setPid($pid) {
		$this->pid = $pid;
	}

	/**
	 * Returns the statistic's pid.
	 *
	 * @return string The statistic's pid
	 */
	public function getPid() {
		return $this->pid;
	}

	/**
	 * Sets this statistic's begintime.
	 *
	 * @param string $begintime The statistic's begintime
	 * @return void
	 */
	public function setBegintime($begintime) {
		$this->begintime = $begintime;
	}

	/**
	 * Returns the statistic's begintime.
	 *
	 * @return string The statistic's begintime
	 */
	public function getBegintime() {
		return $this->begintime;
	}
		
}
?>
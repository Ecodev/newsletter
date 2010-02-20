<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Xavier Perseguers <typo3@perseguers.ch>
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
 * Service to handle settings that are available in ExtJS.
 *
 * @category    ExtJS
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Xavier Perseguers <typo3@perseguers.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_ExtJS_SettingsService {

	/**
	 * @var string
	 */
	protected $extJSNamespace;

	/**
	 * @var settings
	 */
	protected $settings = array();

	/**
	 * Default constructor
	 *
	 * @param string $namespace
	 */
	public function __construct($extJSNamespace) {
		$this->extJSNamespace = $extJSNamespace;
	}

	/**
	 * Returns an ExtJS setting.
	 *
	 * @param string $name
	 * @return mixed
	 * @api
	 */
	public function getExtJS($name) {
		return $this->extJSNamespace .  '.settings.' . $name;
	}

	/**
	 * Assigns a setting.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @api
	 */
	public function assign($name, $value) {
		$this->settings[$name] = $value;
	}

	/**
	 * Returns the number of ExtJS settings.
	 *
	 * @return integer
	 */
	public function count() {
		return count($this->settings);
	}

	/**
	 * Serializes the settings to get an ExtJS code snippet.
	 *
	 * @return string
	 */
	public function serialize() {
		return $this->extJSNamespace . '.settings = ' . json_encode($this->settings) . ';';
	}

}
?>
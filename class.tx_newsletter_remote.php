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
*
* $Id$
***************************************************************/

/**
 * Classes used as ExtDirect's router
 *
 * @author	Fabien Udriot <fabien.udriot@ecodev.ch>
 * @package	TYPO3
 * @subpackage	tx_newsletter
 */
class tx_newsletter_remote {

	/**
	 * Extension Key name
	 *
	 * @var string
	 */
	protected $extensionKey = 'newsletter';
	
	/**
	 * Get / Post parameters
	 *
	 * @var array
	 */
	protected $parameters = array();

	/**
	 * Extension configuration
	 *
	 * @var array
	 */
	protected $configurations = array();

	/**
	 * Constructor
	 *
	 * @global Language $LANG;
	 */
	public function __construct() {
		global $LANG;
		$this->parameters = array_merge(t3lib_div::_GET(), t3lib_div::_POST());

		// Load language
		$LANG->includeLLFile('EXT:newsletter/Resources/Private/Language/locallang.xml');

		// Get extension configuration
		$this->configurations = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['newsletter']);
	}
	
	/**
	 * This method returns the message's content
	 *
	 * @param	array			$PA: information related to the field
	 * @param	t3lib_tceform	$fobj: reference to calling TCEforms object
	 * @return	string	The HTML for the form field
	 */
	public function concatenateStrings($string1, $string2) {
		return $string1 . ' ' . $string2;
	}

	/**
	 * Enter description here ...
	 * @formHandler
	 */
	public function getFormData()	{
	
		return "ads";
	}
}

?>
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
 * A ViewHelper which formats the newsletter label
 *
 * = Examples =
 * 
 * <f:format.statisticLabel record="{record}"/>
 * 
 * @category    ViewHelpers
 * @package     TYPO3
 * @subpackage  tx_Newsletter
 * @author      Fabien Udriot <fabien.udriot@ecodev.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_Newsletter_ViewHelpers_Format_StatisticLabelViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * This method formats the newsletter list
	 *
	 * @param array $record that contains all necessary information
	 * @return	string	content to display
	 */
	public function render($record = array()) {
		global $LANG;

		// Load language
		$LANG->includeLLFile('EXT:newsletter/Resources/Private/Language/locallang.xml');
		
		$result = $LANG->getLL('title');
		$result .= ' ' . date($GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'], $record['begintime']);
		$result .= '@' . date($GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'], $record['begintime']);
		$result .= ' - ' . $record['number_of_recipients'];
		$result .= ' ' . $LANG->getLL('recipients');
		return $result;
	}

}
?>
<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Fabien Udriot <fabien.udriot@ecodev.ch>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
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
 * Class for updating the data query
 *
 * @author		Fabien Udriot <fabien.udriot@ecodev.ch>
 * @package		TYPO3
 * @subpackage	tx_newsletter
 *
 * $Id$
 */
class ext_update {

	/**
	 * Main function, returning the HTML content of the module
	 *
	 * @return	string	HTML to display
	 */
	function main() {
		$update = t3lib_div::_GP('submitButton');
			// The update button was clicked
		if (!empty($update)) {
			$updateList = t3lib_div::_GP('correctedConfiguration');
			$tceData = array('tx_newsletter_filters' => array());
			foreach ($updateList as $uid => $newConfiguration) {
				$tceData['tx_newsletter_filters'][$uid] = array('configuration' => $newConfiguration);
			}
			$tce = t3lib_div::makeInstance('t3lib_TCEmain');
			$tce->stripslashes_values = 0;
			$tce->start($tceData, array());
			$tce->process_datamap();
		}
		$content = '<h2>Check for old special values syntax</h2>';
			// Get all records with a non-empty configuration field
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid, title, configuration', 'tx_newsletter_filters', "configuration <> ''");
			// There are none
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) {
			$content .= '<p>No filters are used.</p>';

			// The additional SQL field is not empty for some records, propose update
		} else {
			$updates = array();
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$configuration = $row['configuration'];
				$matches = array();
				$result = preg_match_all('/(\\\?(empty|null|clear_cache))/', $configuration, $matches, PREG_OFFSET_CAPTURE);
				if (!empty($result)) {
						// If at least one match doesn't start with a backslash, an update is needed
					foreach ($matches[0] as $aMatch) {
						if (strpos($aMatch[0], '\\') !== 0) {
							$updates[] = $row;
							break;
						}
					}
				}
			}
			if (count($updates) == 0) {
				$content .= '<p>No changes needed</p>';
			} else {
				$content .= '<p>The following records use an old value syntax. They should be updated. Correct the value in each field and press the update button</p>';
				$content .= '<form name="updateForm" action="" method ="post">';
				$content .= '<table cellpadding="4" cellspacing="0" border="1">';
				$content .= '<thead><tr><th>Record</th><th>Configuration</th><th>Replacement</th></tr></thead>';
				$content .= '<tbody>';
				foreach ($updates as $row) {
					$uidList .= $row['uid'];
					$content .= '<tr valign="top">';
					$content .= '<td>' . $row['title'] . ' [' . $row['uid'] . ']</td>';
					$content .= '<td>' . nl2br($row['configuration']) . '</td>';
					$content .= '<td><textarea name="correctedConfiguration[' . $row['uid'] . ']" cols="100" rows="5">' . $row['configuration'] . '</textarea></td>';
					$content .= '</tr>';
				}
				$content .= '</tbody>';
				$content .= '</table>';
					// Display update form, if the update button was not already clicked
				$content .= '<p><input type="submit" name="submitButton" value ="Update"></p>';
				$content .= '</form>';
			}
		}
		return $content;
	}

	/**
	 * This method checks whether it is necessary to display the UPDATE option at all
	 *
	 * @param	string	$what: What should be updated
	 */
	function access($what = 'all') {
		return TRUE;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dataquery/class.ext_update.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dataquery/class.ext_update.php']);
}
?>

<?php

/**
 * Handle bounced emails. Fetch them, analyse them and take approriate actions.
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Newsletter_Tca_EmailTca {

	/**
	 * Returns an HTML table showing recipient_data content
	 *
	 * @param $PA
	 * @param $fObj
	 */
	public function render($PA, $fObj) {
		$data = unserialize($PA['row']['recipient_data']);
		$keys = array_keys($data);

		$result = '<table style="border: 1px grey solid; border-collapse: collapse;">';
		$result .= '<tr>';
		foreach ($keys as $key) {
			$result .= '<th style="padding-right: 1em;">' . $key . '</th>';
		}
		$result .= '</tr>';

		$result .= '<tr style="border: 1px grey solid; border-collapse: collapse;">';
		foreach ($data as $value) {
			$result .= '<td style="padding-right: 1em;">' . $value . '</td>';
		}
		$result .= '</tr>';
		$result .= '</table>';

		return $result;
	}
}

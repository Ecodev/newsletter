<?php


class Tx_Newsletter_ViewHelpers_CsvValuesViewHelper extends Tx_MvcExtjs_ViewHelpers_AbstractViewHelper {

	/**
	 * Format array of values to CSV format
	 *
	 * @param array $values array of values to output in CSV format
	 * @return string
	 */
	public function render(array $values) {
		
		return t3lib_div::csvValues($values);
	}
}
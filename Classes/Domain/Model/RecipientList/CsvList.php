<?php


class Tx_Newsletter_Domain_Model_RecipientList_CsvList extends Tx_Newsletter_Domain_Model_RecipientList_CsvFile
{
	/**
	 * csvValues
	 *
	 * @var string $csvValues
	 */
	protected $csvValues;

	/**
	 * Setter for csvValues
	 *
	 * @param string $csvValues csvValues
	 * @return void
	 */
	public function setCsvValues($csvValues) {
		$this->csvValues = $csvValues;
	}

	/**
	 * Getter for csvValues
	 *
	 * @return string csvValues
	 */
	public function getCsvValues() {
		return $this->csvValues;
	}
	
	function init()
	{
		$this->loadCsvFromData($this->getCsvValues());
	}
}

<?php

class Tx_Newsletter_Domain_Model_RecipientList_CsvUrl extends Tx_Newsletter_Domain_Model_RecipientList_CsvFile
{
	/**
	 * csvUrl
	 *
	 * @var string $csvUrl
	 */
	protected $csvUrl;

	/**
	 * Setter for csvUrl
	 *
	 * @param string $csvUrl csvUrl
	 * @return void
	 */
	public function setCsvUrl($csvUrl) {
		$this->csvUrl = $csvUrl;
	}

	/**
	 * Getter for csvUrl
	 *
	 * @return string csvUrl
	 */
	public function getCsvUrl() {
		return $this->csvUrl;
	}
	
	function init()
	{
		$this->loadCsvFromFile($this->getCsvUrl());
	}
}

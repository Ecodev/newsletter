<?php


class Tx_Newsletter_Domain_Model_RecipientList_CsvList extends Tx_Newsletter_Domain_Model_RecipientList_CsvFile
{
	function init()
	{
		$this->loadCsvFromData($this->getCsvValues());
	}
}

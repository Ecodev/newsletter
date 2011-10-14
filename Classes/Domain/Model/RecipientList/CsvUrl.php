<?php

class Tx_Newsletter_Domain_Model_RecipientList_CsvUrl extends Tx_Newsletter_Domain_Model_RecipientList_CsvFile
{
	function init()
	{
		$this->loadCsvFromFile($this->getCsvUrl());
	}
}

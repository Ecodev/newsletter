<?php

/**
 * Provides compatiblity with PHP 5.2.9 because 'str_getcsv' was only introduced in PHP 5.3.0 
 */
if (!function_exists('str_getcsv'))
{
	function str_getcsv($input, $delimiter = ',', $enclosure = '"', $notUsed = null)
	{
		$temp = fopen("php://memory", "rw");
		fwrite($temp, $input);
		fseek($temp, 0);
		$r = fgetcsv($temp, 0, $delimiter, $enclosure);
		fclose($temp);
			
		return $r;
	} 
}

class Tx_Newsletter_Domain_Model_RecipientList_CsvFile extends Tx_Newsletter_Domain_Model_RecipientList_Array
{
	function init()
	{
		$this->loadCsvFromFile(PATH_site . 'uploads/tx_newsletter/' . $this->fields['csv_filename']);
	}

	/**
	 * Load data from a CSV file. 
	 * @param $filename path to the CSV file may be on disk or remote URL
	 */
	protected function loadCsvFromFile($filename)
	{
		$csvdata = null;
		if ($filename)
		{
			$csvdata = t3lib_div::getURL($filename);
		} 
		
		$this->loadCsvFromData($csvdata);
	}
	
	/**
	 * Load data from a CSV data. 
	 * @param $csvdata CSV data
	 */
	protected function loadCsvFromData($csvdata)
	{
		$this->data = array();
		
		$sepchar = $this->fields['csv_separator'] ? $this->fields['csv_separator'] : ',';
		$keys = array_map ('trim', explode($sepchar, $this->fields['csv_fields']));
		
		if ($csvdata && $sepchar && count($keys))
		{
			$lines = explode("\n", $csvdata);
			foreach ($lines as $line)
			{
				$values = str_getcsv($line, $sepchar);
				$row = array_combine($keys, $values);

				if ($row)
				{
					$this->data[] = $row;
				}
			}
		}
	}
	
}


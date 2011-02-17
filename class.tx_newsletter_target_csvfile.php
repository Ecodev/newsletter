<?php

require_once(t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_target_array.php');

class tx_newsletter_target_csvfile extends tx_newsletter_target_array
{
	function init()
	{
		$this->loadCsvFromFile(PATH_site . 'uploads/tx_newsletter/' . $this->fields['csvfilename']);
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
		
		$sepchar = $this->fields['csvseparator'] ? $this->fields['csvseparator'] : ',';
		$keys = array_map ('trim', explode($sepchar, $this->fields['csvfields']));
		
		if ($csvdata && $sepchar && count($keys))
		{
			$lines = str_getcsv($csvdata, "\n");			
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


<?php

require_once(t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_target_csvfile.php');

class tx_newsletter_target_csvlist extends tx_newsletter_target_csvfile
{
	function init()
	{
		$this->loadCsvFromData($this->fields['csvvalues']);
	}
}

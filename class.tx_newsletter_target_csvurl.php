<?php

require_once(t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_target_csvfile.php');

class tx_newsletter_target_csvurl extends tx_newsletter_target_csvfile
{
	function init()
	{
		$this->loadCsvFromFile($this->fields['csvurl']);
	}
}

<?php

require_once (t3lib_extMgm::extPath('tcdirectmail').'class.tx_tcdirectmail_plain.php');

class tx_tcdirectmail_plain_lynx extends tx_tcdirectmail_plain {
	var $fetchMethod = 'url';

	function setHtml($url) {
		exec (tx_tcdirectmail_tools::confParam('path_to_lynx').' -dump "'.$url.'"', $output);
		$this->plainText = implode("\n", $output);
	}
}



?>

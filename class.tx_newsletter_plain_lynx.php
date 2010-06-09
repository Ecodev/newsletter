<?php

require_once (t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_plain.php');

class tx_newsletter_plain_lynx extends tx_newsletter_plain {
	var $fetchMethod = 'url';

	function setHtml($url) {
		exec (tx_newsletter_tools::confParam('path_to_lynx').' -dump "'.$url.'"', $output);
		$this->plainText = implode("\n", $output);
	}
}



?>

<?php

require_once(t3lib_extMgm::extPath('newsletter').'/3dparty/class.html2text.inc');

class Tx_Newsletter_Domain_Model_PlainConverter_Builtin extends html2text implements Tx_Newsletter_Domain_Model_IPlainConverter
{	
	public function setContent($content, $contentUrl, $baseUrl)
	{
		$this->set_base_url($baseUrl);
		$this->set_html($content);
	}
	
	public function getPlainText()
	{	
		return $this->get_text();
	}
}


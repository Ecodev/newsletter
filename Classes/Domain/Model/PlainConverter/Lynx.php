<?php

class Tx_Newsletter_Domain_Model_PlainConverter_Lynx implements Tx_Newsletter_Domain_Model_IPlainConverter
{	
	private $url = null;
	
	public function setContent($content, $contentUrl, $baseUrl)
	{
		$this->url = $contentUrl;
	}
	
	public function getPlainText()
	{
		exec(Tx_Newsletter_Tools::confParam('path_to_lynx') . ' -dump "' . $this->url . '"', $output);
		$plainText = implode("\n", $output);		
		return $plainText;
	}
}


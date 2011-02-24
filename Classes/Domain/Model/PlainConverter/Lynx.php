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
		exec(tx_newsletter_tools::confParam('path_to_lynx').' -dump "'.$url.'"', $output);
		$plainText = implode("\n", $output);		
		return $plainText;
	}
}


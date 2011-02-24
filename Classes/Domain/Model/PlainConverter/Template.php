<?php

class Tx_Newsletter_Domain_Model_PlainConverter_Template implements Tx_Newsletter_Domain_Model_IPlainConverter
{	
	private $url = null;
	
	public function setContent($content, $contentUrl, $baseUrl)
	{
		$this->url = $contentUrl;
	}
	
	public function getPlainText()
	{
		$plainText = tx_newsletter_tools::getURL($this->url . '&type=99');
		return $plainText;
	}
}


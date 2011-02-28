<?php

require_once(t3lib_extMgm::extPath('newsletter').'/3dparty/class.html2text.inc');

class Tx_Newsletter_Domain_Model_PlainConverter_Builtin extends html2text implements Tx_Newsletter_Domain_Model_IPlainConverter
{	
	private $links = array();
	
	public function setContent($content, $contentUrl, $baseUrl)
	{
		$this->set_base_url($baseUrl);
		$this->set_html($content);
	}
	
	public function getPlainText()
	{	
		return $this->get_text();
	}
	
	function _convert()
	{
		$this->links = array();
		return parent::_convert();
	}
	
	/**
	 * Override parent function to make links unique
	 * @see 3dparty/html2text::_build_link_list()
	 */
	function _build_link_list( $link, $display )
    {
    	// If links already exists, return its existing reference
    	if (array_key_exists($link, $this->links))
    	{
    		return $display . ' [' . $this->links[$link] . ']';
    	}
    	// Otherwise call the parent and memorize returned reference
    	else
    	{
    		$result = parent::_build_link_list($link, $display);
    		
    		preg_match('/\[(\d+)\]$/', $result, $m);
    		$reference = $m[1];
    		
    		$this->links[$link] = $reference;
    		return $result;
    	}
    }
}


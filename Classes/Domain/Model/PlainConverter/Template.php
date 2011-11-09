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
		$plainText = self::getURL($this->url . '&type=99');
		return $plainText;
	}
	
	
	/**
	 * Get dynamic TYPO3 content in a safe way.
	 *
	 * This is just a wrapper to t3lib_div::getURL that will abort with a silent die, if the content seems strange. This is to prevent 
	 * error-filled content being sent to the receivers. This is only used for fetching dynamic text or html content with. It should *not*
	 * be used to fetch CSS, images or other static content.
	 * 
	 * @param string $url URL of content
	 * @return string Returned content
	 * 
	 */
	protected static function getURL($url) {
		$content = t3lib_div::getURL($url);

		/* Content should be more that just a few characters. Apache error propably occured */
		if (strlen($content) < 200) {
			die("Newsletter failure ($url): Content too short. The content must be at least 200 chars long to be considered valid.");
		}

		/* Content should not contain PHP-Warnings */
		if (substr($content, 0, 22) == "<br />\n<b>Warning</b>:") {
			die("Newsletter failure ($url): Content contains PHP Warnings. This must not reach the receivers.");
		}

		/* Content should not contain PHP-Warnings */
		if (substr($content, 0, 26) == "<br />\n<b>Fatal error</b>:") {
			die("Newsletter failure ($url): Content contains PHP Fatal errors. This must not reach the receivers.");
		}

		/* If the page contains a "Pages is being generared" text... this is bad too */
		if (strpos($content, 'Page is being generated.') && strpos($content, 'If this message does not disappear within')) {
			die("Newsletter failure ($url): Content contains \"wait\" signatures. This must not reach the receivers.");
		}

		return $content;
	}

}


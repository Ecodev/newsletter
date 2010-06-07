<?php
/*************************************************************** 
*  Copyright notice 
* 
*  (c) 2006-2008 Daniel Schledermann <daniel@schledermann.net> 
*  All rights reserved 
* 
*  This script is part of the TYPO3 project. The TYPO3 project is 
*  free software; you can redistribute it and/or modify 
*  it under the terms of the GNU General Public License as published by 
*  the Free Software Foundation; either version 2 of the License, or 
*  (at your option) any later version. 
* 
*  The GNU General Public License can be found at 
*  http://www.gnu.org/copyleft/gpl.html. 
* 
*  This script is distributed in the hope that it will be useful, 
*  but WITHOUT ANY WARRANTY; without even the implied warranty of 
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
*  GNU General Public License for more details. 
* 
*  This copyright notice MUST APPEAR in all copies of the script! 
***************************************************************/


/**
 * This is the holy inner core of tcdirectmail. 
 * It is normally used in an instance per language to compile MIME 1.0 compatible mails
 */
class tx_tcdirectmail_mailer {
	/* Vars that might need to be overridden */
	var $senderName = "Test Testermann";
	var $senderEmail = "test@test.net";
	var $bounceAddress = 'bounce@test.test';
	var $siteUrl = "http://www.test.test/";
	var $homeUrl = "www.test.test/typo3conf/ext/tcdirectmail/";

	/**
	 * Constructor that set up basic internal datastructures. Do not call directly
	 *
	 */
	function tx_tcdirectmail_mailer () {
		global $TYPO3_CONF_VARS;
    
		/* Determine the supposed hostname */
		if( ini_get('safe_mode') || TYPO3_OS == 'WIN'){
			$this->hostname = $_SERVER['HTTP_HOST'];
		} else {
			$this->hostname = trim(exec('hostname'));
		}
        
		/* Read some basic settings */
		$this->extConf = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['tcdirectmail']);
		$this->realPath = PATH_site;
		$this->inlinefiles = array();
		$this->inlinemimes = array();
		$this->files = array();
		$this->mimes = array();      
	      
		/* Generate some boundaries for the mime-mails and make them interesting for geeks to look at */
		$boundary_hash = substr(md5(time()), 0,10);
		switch (rand(0,9)) {
			case 0 : $this->boundaries = array('We-come-in-peace','Dont-run-we-are-your-friends','Take-me-to-your-leader'); break;
			case 1 : $this->boundaries = array('Im-not-looking-for-a-friend', 'Im-looking-for-a-Jedi-master','You-seek-yoda'); break;
			case 2 : $this->boundaries = array('Your-culture-will-adapt-to-service-us', 'Resistance-is-futile', 'We-are-the-borg'); break;
			case 3 : $this->boundaries = array('looks-can-be-deceiving','we-are-not-here-because-we-are-free', 'we-are-here-because-we-are-not-free'); break;
			case 4 : $this->boundaries = array('The-best-thing-about-me','is-that-there-is-so-many-of-me', 'me,-me,-me'); break;
			case 5 : $this->boundaries = array('Open-the-door-please-HAL', 'Im-sorry-Dave','Im-afraid-I-cant-do-that'); break;
			case 6 : $this->boundaries = array('What-do-you-mean---they-cut-the-power', 'how-could-they-cut-the-power', 'They-are-animals-mans'); break;
			case 7 : $this->boundaries = array('Use-the-force-luke','The-Force-will-be-with-you--always','I-felt-a-great-disturbance-in-the-Force'); break;
			case 8 : $this->boundaries = array('I-am-your-father','Search-your-feelings','You-know-it-to-be-true'); break;
			case 9 : $this->boundaries = array('1.Write-mass-mailing-software','2.???????','3.profit'); break;
		}
      
		$this->boundaries[0] = '----'.$this->boundaries[0].'--'.$boundary_hash;
		$this->boundaries[1] = '----'.$this->boundaries[1].'--'.$boundary_hash;
		$this->boundaries[2] = '----'.$this->boundaries[2].'--'.$boundary_hash;
	      
		/* Set up mail replacement hooks */
		if (is_array($TYPO3_CONF_VARS['EXTCONF']['tcdirectmail']['mailReplacementHook'])) {
			foreach ($TYPO3_CONF_VARS['EXTCONF']['tcdirectmail']['mailReplacementHook'] as $_classRef) {
				$this->mailReplacers[] = & t3lib_div::getUserObj($_classRef);
			}
		}
	}
    
	/**
	 * Encodes a text in qouted printable format. Either via build in function, with fallback to a php-implementation.
	 *
	 * @static
	 * @param    string      Text to be encoded
	 * @return   string      Encoded text
	 */
	function qoutedPrintableEncode($ascii_in = "") {
		if(strtolower($this->charset) != 'utf-8' && function_exists('imap_8bit')){
			return imap_8bit($ascii_in);
		} else {
			return t3lib_div::quoted_printable($ascii_in);
		}
	}
	

	/**
	 * Determine the charset encoding of the mail.
	 *
	 * @internal
	 * @return   string      Determined charset encoding
	 */
	function getCharsetEncoding() {
		/* Does the code come with anything? */
		if (preg_match ('|<meta http-equiv="Content-Type" content="text/html; charset=([^"]*)" />|', $this->html_tpl, $match)) {
			return $match[1];
		}
       
		/* Is anything provided in TYPO3_CONF_VARS? */
		if (trim($GLOBALS['TYPO3_CONF_VARS']['BE']['forceCharset'])) {
			return $GLOBALS['TYPO3_CONF_VARS']['BE']['forceCharset'];
		}
       
		/* Oh well..  We'll just have to guess something reasonable */
		return 'iso-8859-1';
	}

	/**
	 * Get mime-type for a file
	 *
	 * @static
	 * @return    string      Mime type determined.
	 */
	function getMimeType($filename) {
		if (function_exists('mime_content_type')) {
			return mime_content_type ($filename);
		} else if (function_exists('finfo_file')) {
			$finfo    = finfo_open(FILEINFO_MIME);
			$mimetype = finfo_file($finfo, $filename);
			finfo_close($finfo);
        		return $mimetype;
		} else if (!ini_get('safe_mode')) {
			return trim ( exec ('file -bi '.escapeshellarg($filename)));
		} else {
			die ("Arghh... methods of determining mimetypes exhaustet. Please consider any of: adding legacy function 'mime_content_type', installing 'fileinfo' pecl-extension or allowing shell access to unix 'file'-utility. You can not send out mails containing any files until you resolve this issue.");
		}
	}

	/**
	 * Add a file attachement to the mail
	 *
	 * @param   string      Filename of file to attach.
	 * @return   void
	 */
   
	function addAttachment($filename) {
		if (trim($filename) != '') {
			$path = explode ('/', $filename);
			$basename = array_pop ($path);
			$this->files[$basename] = base64_encode(t3lib_div::getURL($filename));
			$this->mimes[$basename] = $this->getMimeType($filename);
		}
	}

	/**
	 * Set the title text of the mail
	 *
	 * @param   string      The title
	 * @return   void
	 */
	function setTitle($src) {
		/* Detect what markers we need to substitute later on */
		preg_match_all ('/###[\w]+###/', $src, $fields);
		$this->titleMarkers = str_replace ('###', '', $fields[0]);

		/* Any advanced markers we need to sustitute later on */
		$this->titleAdvancedMarkers = array();
		preg_match_all ('/###:IF: (\w+) ###/U', $src, $fields);
		foreach ($fields[1] as $field) {
			$this->titleAdvancedMarkers[] = $field;
		}

		$this->title_tpl = $src;
		$this->title = $src;
	}

	/**
	 * Set the plain text content on the mail
	 *
	 * @param   string      The plain text content of the mail
	 * @return   void
	 */
	function setPlain ($src) {
		/* Remove html-comments */
		$src = preg_replace('/<!--.*-->/U', '', $src);
      
		/* Detect what markers we need to substitute later on */
		preg_match_all ('/###[\w]+###/', $src, $fields);
		$this->plainMarkers = str_replace ('###', '', $fields[0]);
      
		/* Any advanced markers we need to sustitute later on */
		$this->plainAdvancedMarkers = array();
		preg_match_all ('/###:IF: (\w+) ###/U', $src, $fields);
		foreach ($fields[1] as $field) {
			$this->plainAdvancedMarkers[] = $field;
		}

		$this->plain_tpl = $src;
		$this->plain = $src;
	}
    
	/**
    	 * Set the html content on the mail
	 *
	 * @param   string      The html content of the mail
	 * @return   void
	 */    
	function setHtml ($src) {
		/* Find linked css and convert into a style-tag */
		preg_match_all('|<link rel="stylesheet" type="text/css" href="([^"]+)"[^>]+>|Ui', $src, $urls);
		foreach ($urls[1] as $i => $url) {
			$get_url = str_replace($this->siteUrl, '', $url);
			$src = str_replace ($urls[0][$i], 
				"<style type=\"text/css\">\n<!--\n"
				.t3lib_div::getURL($this->realPath.str_replace($this->siteUrl, '', $url))
				."\n-->\n</style>", $src);
		}

		/* We cant very well have attached javascript in a newsmail ... removing */
		$src = preg_replace('|<script type="text/javascript" src="[^"]+"></script>|', '', $src);

		/* Convert external file resouces to attached filer or correct their links */
		$replace_regs = array(
				'/ src="([^"]+)"/',
				'/ background="([^"]+)"/',
		);

		/* Attach */
		if ($this->extConf['attach_images']) {
			foreach ($replace_regs as $replace_reg) {
				preg_match_all($replace_reg, $src, $urls);
				foreach ($urls[1] as $i => $url) {
					$get_url = str_replace($this->siteUrl, '', $url);
					$name = explode ('.', $get_url);
					$ext = array_pop ($name);
					$substname = substr(md5($url), 0, 10).'.'.$ext;
					$this->inlinefiles[$substname] = base64_encode(t3lib_div::getURL("$this->realPath$get_url"));
					$this->inlinemimes[$substname] = $this->getMimeType("$this->realPath$get_url");
					$src = str_replace ($urls[0][$i], str_replace($url, "cid:$substname", $urls[0][$i]), $src);
				}
			}
		/* Or correct link */
		} else {
			foreach ($replace_regs as $replace_reg) {
				preg_match_all($replace_reg, $src, $urls);
				foreach ($urls[1] as $i => $url) {
					if (!preg_match('|^http://|', $url)) {
						$src = str_replace ($urls[0][$i], str_replace($url, $this->siteUrl.$url, $urls[0][$i]), $src);
					}
				}
			}
		}   

		/* Fix relative links */
		preg_match_all ('|<a [^>]*href="(.*)"|Ui', $src, $urls);
		foreach ($urls[1] as $i => $url) {
			/* If this is already a absolute link, dont replace it */
			if (!preg_match('|^http://|', $url) && !preg_match('|^mailto:|', $url) && !preg_match('|^#|', $url)) {
				$replace_url = str_replace($url, $this->siteUrl.$url, $urls[0][$i]);
				$src = str_replace ($urls[0][$i], $replace_url, $src);
			}
		}
      
		/* Detect what markers we need to substitute later on */
		preg_match_all ('/###[\w]+###/', $src, $fields);
		$this->htmlMarkers = str_replace ('###', '', $fields[0]);
      
		/* Any advanced IF fields we need to sustitute later on */
		$this->htmlAdvancedMarkers = array();
		preg_match_all ('/###:IF: (\w+) ###/U', $src, $fields);
		foreach ($fields[1] as $field) {
			$this->htmlAdvancedMarkers[] = $field;
		}

		$this->html_tpl = $src;
		$this->html = $src;
		$this->charset = $this->getCharsetEncoding();
	}
    
	/** 
	 * Tell the caller what markers are required by the mailers content
	 *
	 * @return   array   Array with the fields from html, plain and title.
	 */
	function getMarkers() {
		return array_unique(array_merge($this->htmlAdvancedMarkers,
				$this->plainAdvancedMarkers,
				$this->titleAdvancedMarkers,
				$this->htmlMarkers,
				$this->plainMarkers,
				$this->titleMarkers));
	}
   
	/**
	 * Insert a "mail-open-spy" in the mail for test.
	 *
	 * @return   void
	 */
	function testSpy () {
		$this->html = str_replace (
					'</body>', 
					'<div><img src="'.$this->siteUrl.'typo3/clear.gif" width="0" height="0" /></div></body>', 
					$this->html);
	}
    
	/**
	 * Insert a "mail-open-spy" in the mail for real. This relies on the $this->authcode being set.
	 *
	 * @return   void
	 */
	function insertSpy($authCode, $sendid) {
		$this->html = str_replace (
				'</body>', 
				'<div><img src="'.$this->homeUrl.'web/beenthere.php?c='.$authCode.'&s='.$sendid.'" width="0" height="0" /></div></body>',
				$this->html);
	}
    
	/**
	 * Reset all modifications to the content.
	 *
	 * @return   void
	 */
	function resetMarkers() {
		$this->html  = $this->html_tpl;
		$this->plain = $this->plain_tpl;
		$this->title = $this->title_tpl;
	}
    
	/**
	 * Replace a named marker with a suppied value. 
	 * A marker can have the form of a simple string marker ###marker###
	 * Or a advanced boolean marker ###:IF: marker ### ..content.. (###:ELSE:###)? ..content.. ###:ENDIF:###
	 *
	 * @param   string      Name of the marker to replace
	 * @param   string      Value to replace marker with.
	 * @return   void
	 */
	function substituteMarker($name, $value) {
		/* For each marker, only substitute if the field is registered as a marker. This approach has shown to 
		 speed up things quite a bit.  */
		if (in_array($name, $this->htmlAdvancedMarkers)) {
			$this->html = tx_tcdirectmail_mailer::advancedSubstituteMarker($this->html, $name, $value);
		}

		if (in_array($name, $this->plainAdvancedMarkers)) {
			$this->plain = tx_tcdirectmail_mailer::advancedSubstituteMarker($this->plain, $name, $value);
		}

		if (in_array($name, $this->titleAdvancedMarkers)) {
			$this->title = tx_tcdirectmail_mailer::advancedSubstituteMarker($this->title, $name, $value);
		}

		if (in_array($name, $this->htmlMarkers)) {
			$this->html  = str_replace("###$name###", $value, $this->html);
		}

		if (in_array($name, $this->plainMarkers)) {
			$this->plain = str_replace("###$name###", $value, $this->plain);
		}

		if (in_array($name, $this->titleMarkers)) {
			$this->title = str_replace("###$name###", $value, $this->title);
		}
	}

	/**
	 * Substitute an advanced marker.
	 *
	 * @internal
	 * @param   string      Source to apply marker substitution to.
	 * @param   string      Name of marker.
	 * @param   boolean      Display value of marker.
	 * @return   string      Source with applied marker.
	 */
	function advancedSubstituteMarker ($src, $name, $value) {
		preg_match_all("/###:IF: $name ###([\w\W]*)###:ELSE:###([\w\W]*)###:ENDIF:###/U", $src, $matches);
		foreach ($matches[0] as $i => $full_mark) {
			if ($value) {
				$src = str_replace($full_mark, $matches[1][$i], $src);
			} else {
				$src = str_replace($full_mark, $matches[2][$i], $src);
			}
		}

		preg_match_all("/###:IF: $name ###([\w\W]*)###:ENDIF:###/U", $src, $matches);
		foreach ($matches[0] as $i => $full_mark) {
			if ($value) {
				$src = str_replace($full_mark, $matches[1][$i], $src);
			} else {
				$src = str_replace($full_mark, '', $src);
			}
		}

		return $src;
	}

	/**
	 * Apply multiple markers to mail contents
	 *
	 * @param   array      Assoc array with name => value pairs.
	 * @return   void
	 */
	function substituteMarkers ($record) {
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tcdirectmail']['substituteMarkersHook'])) {
			foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tcdirectmail']['substituteMarkersHook'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$this->html = $_procObj->substituteMarkersHook($this->html, 'html', $record);
				$this->plain = $_procObj->substituteMarkersHook($this->plain, 'plain', $record);
				$this->title = $_procObj->substituteMarkersHook($this->title, 'title', $record);
			}
		}      
      
		foreach ($record as $name => $value) {
			$this->substituteMarker($name, $value);
		}
	}
    
	/**
	 * Replace all links in the mail to make spy links.
	 *
	 * @param    string     Encryption code for the links
	 * @return   array      Data structure with original links.
	 */
	function makeClickLinks ($authCode, $sendid) {
		$links['plain'] = array();
		$links['html'] = array();

		/* Exchange all http:// links  html */
		preg_match_all ('|<a [^>]*href="(http://[^"]*)"|Ui', $this->html, $urls);
		foreach ($urls[1] as $i => $url) {
			$links['html'][$i] = html_entity_decode($url);
			  
			/* Two step replace to be as precise as possible */
			$link = str_replace($url, $this->homeUrl."web/click.php?l=$i&t=html&c=$authCode&s=$sendid", $urls[0][$i]);      
			$this->html  = str_replace($urls[0][$i], $link, $this->html);
		}

		/* Exchange all http:// links plaintext */
		preg_match_all ('|http://[^ \r\n\)]*|i', $this->plain, $urls);
		foreach ($urls[0] as $i => $url) {
			$links['plain'][$i] = html_entity_decode($url);
			$this->plain = str_replace($url, $this->homeUrl."web/click.php?l=$i&t=plain&c=$authCode&s=$sendid", $this->plain);
		}

		return $links;   
	}

	/**
	 * Replace all links in the mail to make test spy links.
	 * This will create links similar to the real spy links, but does not require any database activity in order to work, 
	 * and does not reveal any information of the receiver.
	 *
	 * @return   void
	 */
	function testClickLinks () {
		/* Exchange all http:// links  html */
		preg_match_all ('|<a [^>]*href="(http://[^"]*)"|Ui', $this->html, $urls);
		foreach ($urls[1] as $i => $url) {
			$link = str_replace($url, $this->homeUrl."web/tclick.php?l=".base64_encode(html_entity_decode($url)), $urls[0][$i]);
			$this->html  = str_replace($urls[0][$i], $link, $this->html);
		}

		/* Exchange all http:// links plaintext */
		preg_match_all ('|http://[^ \n\r\)]*|i', $this->plain, $urls);
		foreach ($urls[0] as $i => $url) {   
			$this->plain = str_replace($url, $this->homeUrl."web/tclick.php?l=".base64_encode($url),$this->plain);
		}
	}
	    
	/**
	 * The regular send method. Use this to send a normal personalized mail.
	 *
	 * @param   array      Record with receivers information as name => value pairs.
	 * @param   array      Array with extra headers to apply to mails as name => value pairs.
	 * @return   void
	 */
	function send ($receiverRecord, $extraHeaders = array()) {
		$this->substituteMarkers($receiverRecord);   
		$this->raw_send($receiverRecord, $extraHeaders);
		$this->resetMarkers();   
	}

	/**
	 * Raw send method. This does not replace markers, or reset the mail afterwards.
	 *
	 * @interal
	 * @param   array      Record with receivers information as name => value pairs.
	 * @param   array      Array with extra headers to apply to mails as name => value pairs.
	 * @return   void
	 */
	function raw_send($receiverRecord, $extraHeaders = array()) {
		global $TYPO3_CONF_VARS;
		$messageId = md5(microtime().$receiverRecord['email']).'@'.$this->hostname;
		$boundary  = &$this->boundaries[0];
		$subboundary = &$this->boundaries[1];
		$terboundary = &$this->boundaries[2];
		$body      = array();
		$headers   = array("From: $this->senderName <$this->senderEmail>", "Message-ID: <$messageId>");

		/* Both sendmail params and the use of bounce mails uses the "-f" parameter */
		/* This is incompatible with other mail-methods than sendmail-interface, so please watch out here */
		if ($this->bounceAddress) {
			$sendmail_params .= "-f $this->bounceAddress ";
		}

		if ($this->extConf['sendmail_params']) {
			$sendmail_params .= $this->extConf['sendmail_params']; 
		}

		foreach ($TYPO3_CONF_VARS['EXTCONF']['tcdirectmail']['extraMailHeaders'] as $header => $value) {
			$headers[] = "$header: $value";
		}

		foreach ($extraHeaders as $header => $value) {
			$headers[] = "$header: $value";
		}

		$headers[] = 'MIME-Version: 1.0';

		/* Plain text? */
		if ($receiverRecord['plain_only'] == 1) {
			/* Attached files? */
			if (count($this->files)) {   
				$headers[] = "Content-Type: multipart/mixed;";
				$headers[] = " boundary=\"$boundary\"";

				/* Standard pre-mime text */
				$body[] = 'This is a multi-part message in MIME format.';
				$body[] = '';

				/* Plain-body for mail with attached files */
				$body[] = '--'.$boundary;      
				$body[] = $this->getPlainChunck();
				$body[] = '';
				$body[] = $this->getAttachedFiles($boundary);
				$body[] = "--$boundary--";   

				/* No attached files? */
			} else {
				$headers[] = 'Content-Type: text/plain; charset="'.$this->charset.'"';
				$headers[] = 'Content-Transfer-Encoding: quoted-printable';
				$body[] = $this->qoutedPrintableEncode(t3lib_div::substUrlsInPlainText($this->plain,'all',$this->siteUrl));
			}

		/* or html */
		} else {
			/* Attached files? */
			if (count($this->files)) {
				$headers[] = 'Content-Type: multipart/mixed; charset="'.$this->charset.'";';
				$headers[] = ' boundary="'.$boundary.'"';          

				/* Standard pre-mime text */
				$body[] = 'This is a multi-part message in MIME format.';
				$body[] = '';

				/* Envelope for the text and html versions */
				$body[] = '--'.$boundary;
				$body[] = 'Content-Type: multipart/alternative;';
				$body[] = ' boundary="'.$subboundary.'"';   
				$body[] = '';

				/* For the alternative html and text versions */
				$body[] = '--'.$subboundary;
				$body[] = $this->getPlainChunck();
				$body[] = '';
				$body[] = '--'.$subboundary;

				/* Do we have inline files or not. If we do, add a multipart/related part for the html-content & inline files */
				if (count($this->inlinefiles)) {   
					$body[] = $this->getHtmlChunckWithFiles ($terboundary);
				} else {
					$body[] = $this->getHtmlChunckWithoutFiles ();
				}

				$body[] = "--$subboundary--";
				$body[] = '';            
				$body[] = $this->getAttachedFiles($boundary);
				$body[] = "--$boundary--";
			/* No attached files */
			} else {
				$headers[] =  'Content-Type: multipart/alternative; charset="'.$this->charset.'";';
				$headers[] =  ' boundary="'.$boundary.'"';
				
				/* Standard pre-mime text */
				$body[] = 'This is a multi-part message in MIME format.';
				$body[] = '';

				/* For the alternative html and text versions */
				$body[] = '--'.$boundary;
				$body[] = $this->getPlainChunck();
				$body[] = '';
				$body[] = '--'.$boundary;

				/* Do we have inline files or not. If we do, add a multipart/related part for the html-content & inline files */
				if (count($this->inlinefiles)) {   
					$body[] = $this->getHtmlChunckWithFiles ($subboundary);
				} else {
					$body[] = $this->getHtmlChunckWithoutFiles ();
				}			
				$body[] = "--$boundary--";
				$body[] = '';
			}
		}

		$title = preg_replace ('/=[=0-9A-Z]+/i', '=?'.strtoupper($this->charset).'?Q?\0?=', $this->qoutedPrintableEncode($this->title));

		/* Hook for actions INSTEAD of actually sending the mail */
		if (is_array($this->mailReplacers)) {
			foreach($this->mailReplacers as $_procObj) {
				$_procObj->mailReplacementHook($receiverRecord['email'], $title, implode("\n", $body), implode("\n", $headers), $this->bounceAddress);
			}
		} else {
			/* Mail it */
			if ($sendmail_params && !ini_get('safe_mode')) {
				mail ($receiverRecord['email'], $title, implode("\n", $body), implode("\n", $headers), $sendmail_params);
			} else {
				mail ($receiverRecord['email'], $title, implode("\n", $body), implode("\n", $headers));
			}
		}
	}

	/** 
	 * Get plain
	 *
	 * @return	string	The plaintext code
	 */
	function getPlainChunck() {
		$body[] = 'Content-Type: text/plain; charset="'.$this->charset.'"';
		$body[] = 'Content-Transfer-Encoding: quoted-printable';
		$body[] = '';
		$body[] = $this->qoutedPrintableEncode(t3lib_div::substUrlsInPlainText($this->plain,'all',$this->siteUrl));

		return implode ("\n", $body);
	}

	/**
 	 * Get html with inline files (multipart/related)
	 *
	 * @param	string	MIME boundary.
	 * @return 	string	The HTML code.
	 */
	function getHtmlChunckWithFiles ($boundary) {
		$body[] = 'Content-Type: multipart/related;';
		$body[] = " boundary=\"$boundary\"";
		$body[] = '';
		$body[] = "--$boundary";
		$body[] = "Content-Type: text/html; charset=\"$this->charset\"";

		if ($this->extConf['html_base64']) {
			$body[] = 'Content-Transfer-Encoding: base64';
			$body[] = '';				
			$body[] = chunk_split(base64_encode($this->html));
		} else {
			$body[] = 'Content-Transfer-Encoding: quoted-printable';
			$body[] = '';			
			$body[] = $this->qoutedPrintableEncode ($this->html);
		}

		/* Get the inline files for use with pictures, stylesheets etc. */
		foreach ($this->inlinefiles as $filename => $content) {
			$body[] = "--$boundary";
			$body[] = 'Content-Type: '.$this->inlinemimes[$filename];
			$body[] = "Content-ID: <$filename>";
			$body[] = 'Content-Transfer-Encoding: base64';
			$body[] = '';
			$body[] = chunk_split($content);
		}

		$body[] = "--$boundary--";
		$body[] = '';

		return implode ("\n", $body);
	}

	/**
	 * Get html without inline files
	 *
	 * @param	string	Charset
	 * @return	string	The HTML code.
	 */
	function getHtmlChunckWithoutFiles () {
		$body[] = "Content-Type: text/html; charset=\"$this->charset\"";

		if ($this->extConf['html_base64']) {
			$body[] = 'Content-Transfer-Encoding: base64';
			$body[] = '';			
			$body[] = chunk_split(base64_encode($this->html));
		} else {
			$body[] = 'Content-Transfer-Encoding: quoted-printable';
			$body[] = '';			
			$body[] = $this->qoutedPrintableEncode ($this->html);
		}

		return implode ("\n", $body);
	}


	/**
	 * Get the MIME encoded attached files of the mail.
	 *
	 * @param   string      MIME boundary to encode the MIME parts with.
	 * @return   string      The MIME encoded files.
	 */
	function getAttachedFiles($boundary) {
		$body = array();

		/* Attach the files */
		foreach ($this->files as $filename => $content) {
			$body[] = "--$boundary";
			$body[] = "Content-Type: ".$this->mimes[$filename].';';
			$body[] = " name=\"$filename\"";
			$body[] = 'Content-Transfer-Encoding: base64';      
			$body[] = "Content-Disposition: inline;";
			$body[] = " filename=\"$filename\"";
			$body[] = '';
			$body[] = chunk_split($content);
		}

		return implode("\n", $body);    
	}
}
?>

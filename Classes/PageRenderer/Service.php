<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Dennis Ahrens <dennis.ahrens@googlemail.com>
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
 * A Service that manipulates t3lib_pagerenderer objects
 * 
 * Note: This feature is experimental!
 * 
 * @category    PageRenderer
 * @package     MvcExtjs
 * @subpackage  PageRenderer
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 * @singleton
 */
class tx_MvcExtjs_PageRenderer_Service {
	
	/**
	 * @var boolean
	 */
	static public $compress = FALSE;
	
	/**
	 * Concatenates all JS-Files contained inside the pagerenderer when
	 * doConcatenate is called on the pagerenderer itself.
	 * 
	 * Registered like this:
	 * 
	 * @params array jsArrays
	 * @return void
	 */
	static public function doConcatenate(array $jsArrays, t3lib_pagerenderer $pageRenderer) {
		$jsCode = '';
		foreach ($jsArrays['jsFiles'] as $relPath => $info) {
			if (substr($relPath,0,'3') === '../') {
				$absPath = t3lib_div::getFileAbsFileName(ltrim($relPath,'./'));
				$jsCode .= file_get_contents($absPath);
				unset($jsArrays['jsFiles'][$relPath]);
			}
		}
		if (self::$compress) {
			$compressedJsCode = t3lib_div::minifyJavaScript($jsCode, $error);
			if ($error === '') {
				$jsCode = $compressedJsCode;
			}
		}
		$pageRenderer->addJsInlineCode('Tx_MvcExtjs_PageRenderer_Service doConcatenate',$jsCode,TRUE,TRUE);
	}
	
}
?>
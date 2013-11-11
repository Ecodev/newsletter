<?php

/* * *************************************************************
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
 * ************************************************************* */

/**
 * View helper which allows you to create ExtBase-based modules in the style of
 * TYPO3 default modules.
 * Note: This feature is experimental!
 *
 * = Examples =
 *
 * <code title="Simple">
 * {namespace ext=Tx_Newsletter_ViewHelpers}
 * <ext:be.container>your additional viewhelpers inside</ext:be.container>
 * </code>
 *
 * Output:
 * "your module content" wrapped with propper head & body tags.
 * Default backend CSS styles and JavaScript will be included
 *
 * <code title="All options">
 * {namespace ext=Tx_Newsletter_ViewHelpers}
 * <ext:be.moduleContainer pageTitle="foo" enableJumpToUrl="false" enableClickMenu="false" loadPrototype="false" loadScriptaculous="false" scriptaculousModule="someModule,someOtherModule" loadExtJs="true" loadExtJsTheme="false" extJsAdapter="jQuery" concatenate="false" compressJs="false" compressCss="false" enableExtJsDebug="true">your module content</f:be.container>
 * </code>
 *
 * @category    ViewHelpers
 * @package     Newsletter
 * @subpackage  ViewHelpers_Be
 * @author      Bastian Waidelich <bastian@typo3.org>
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class Tx_Newsletter_ViewHelpers_Be_ModuleContainerViewHelper extends Tx_Newsletter_ViewHelpers_AbstractViewHelper
{

    /**
     * Renders start page with template.php and pageTitle.
     *
     * @param string  $pageTitle title tag of the module. Not required by default, as BE modules are shown in a frame
     * @param boolean $enableJumpToUrl If TRUE, includes "jumpTpUrl" javascript function required by ActionMenu. Defaults to TRUE
     * @param boolean $loadPrototype specifies whether to load prototype library. Defaults to FALSE
     * @param boolean $loadScriptaculous specifies whether to load scriptaculous libraries. Defaults to FALSE
     * @param string  $scriptaculousModule additionales modules for scriptaculous
     * @param boolean $loadExtJs specifies whether to load ExtJS library. Defaults to TRUE
     * @param boolean $loadExtCore specifies whether to load ExtJS library. Defaults to TRUE
     * @param boolean $loadExtJsTheme whether to load ExtJS "grey" theme. Defaults to TRUE
     * @param string  $extJsAdapter load alternative adapter (ext-base is default adapter)
     * @param boolean $enableExtJsDebug if TRUE, debug version of ExtJS is loaded. Use this for development only.
     * @param boolean $concatenate specifies if the loaded jsFiles should be concatenated into one file. Defaults to TRUE
     * @param boolean $compressJs specifies wether to compress the js. Defaults TRUE
     * @param boolean $compressCss specifies wether to compress the css. Defaults TRUE
     * @param boolean $enableExtJSQuickTips
     * @param string  $extCorePath specifies a path for the ExtCore default NULL (uses the path set in the t3lib_PageRenderer)
     * @param string  $extJsPath specifies a path for the ExtJS default NULL (uses the path set in the t3lib_PageRenderer)
     * @return string
     * @see template
     * @see t3lib_PageRenderer
     */
    public function render($pageTitle = '', $enableJumpToUrl = FALSE, $loadPrototype = FALSE, $loadScriptaculous = FALSE, $scriptaculousModule = '', $loadExtJs = TRUE, $loadExtCore = FALSE, $loadExtJsTheme = TRUE, $extJsAdapter = '', $enableExtJsDebug = FALSE, $concatenate = TRUE, $compressJs = TRUE, $compressCss = TRUE, $enableExtJSQuickTips = TRUE, $extCorePath = NULL, $extJsPath = NULL)
    {

        $doc = $this->getDocInstance();

        if ($enableJumpToUrl === TRUE) {
            $doc->JScode .= '
				<script language="javascript" type="text/javascript">
					script_ended = 0;
					function jumpToUrl(URL)	{
						document.location = URL;
					}
					' . $doc->redirectUrls() . '
				</script>
			';
        }
        if ($loadPrototype === TRUE) {
            $this->pageRenderer->loadPrototype();
        }
        if ($loadScriptaculous === TRUE) {
            $this->pageRenderer->loadScriptaculous($scriptaculousModule);
        }
        if ($extCorePath !== NULL) {
            $this->pageRenderer->setExtCorePath($extCorePath);
        }
        if ($extJsPath !== NULL) {
            $this->pageRenderer->setExtJsPath($extJsPath);
        }
        if ($loadExtJs === TRUE) {
            $this->pageRenderer->loadExtJS(TRUE, $loadExtJsTheme, $extJsAdapter);
            if ($enableExtJsDebug === TRUE) {
                $this->pageRenderer->enableExtJsDebug();
            }
        }
        if ($loadExtCore === TRUE) {
            $this->pageRenderer->loadExtCore();
        }
        if ($enableExtJSQuickTips === TRUE) {
            $this->pageRenderer->enableExtJSQuickTips();
        }

        $this->pageRenderer->addCssFile('sysext/t3skin/extjs/xtheme-t3skin.css');

        $this->renderChildren();

        if ($compressJs === TRUE) {
            $this->pageRenderer->enableCompressJavaScript();
        }
        if ($compressCss === TRUE) {
            $this->pageRenderer->enableCompressCss();
        }
        if ($concatenate === TRUE) {
            $this->pageRenderer->enableConcatenateFiles();
        }
        $output = $doc->startPage($pageTitle);
        $output .= $this->pageRenderer->getBodyContent();
        $output .= $doc->endPage();
        return $output;
    }

}

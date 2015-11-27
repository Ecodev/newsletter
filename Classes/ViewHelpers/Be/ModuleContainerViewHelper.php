<?php

namespace Ecodev\Newsletter\ViewHelpers\Be;

use Ecodev\Newsletter\ViewHelpers\AbstractViewHelper;

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
 * {namespace newsletter=Ecodev\Newsletter\ViewHelpers}
 * <newsletter:be.container>your additional viewhelpers inside</ext:be.container>
 * </code>
 *
 * Output:
 * "your module content" wrapped with propper head & body tags.
 * Default backend CSS styles and JavaScript will be included
 *
 * <code title="All options">
 * {namespace newsletter=Ecodev\Newsletter\ViewHelpers}
 * <newsletter:be.moduleContainer pageTitle="foo" enableJumpToUrl="false" enableClickMenu="false" loadPrototype="false" loadScriptaculous="false" scriptaculousModule="someModule,someOtherModule" loadExtJs="true" loadExtJsTheme="false" extJsAdapter="jQuery" concatenate="false" compressJs="false" compressCss="false" enableExtJsDebug="true">your module content</f:be.container>
 * </code>
 *
 * @author      Bastian Waidelich <bastian@typo3.org>
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class ModuleContainerViewHelper extends AbstractViewHelper
{
    /**
     * Renders start page with template.php and pageTitle.
     *
     * @param string  $pageTitle title tag of the module. Not required by default, as BE modules are shown in a frame
     * @param bool $enableJumpToUrl If TRUE, includes "jumpTpUrl" javascript function required by ActionMenu. Defaults to TRUE
     * @param bool $loadPrototype specifies whether to load prototype library. Defaults to FALSE
     * @param bool $loadScriptaculous specifies whether to load scriptaculous libraries. Defaults to FALSE
     * @param string  $scriptaculousModule additionales modules for scriptaculous
     * @param bool $loadExtJs specifies whether to load ExtJS library. Defaults to TRUE
     * @param bool $loadExtCore specifies whether to load ExtJS library. Defaults to TRUE
     * @param bool $loadExtJsTheme whether to load ExtJS "grey" theme. Defaults to TRUE
     * @param string  $extJsAdapter load alternative adapter (ext-base is default adapter)
     * @param bool $enableExtJsDebug if TRUE, debug version of ExtJS is loaded. Use this for development only.
     * @param bool $concatenate specifies if the loaded jsFiles should be concatenated into one file. Defaults to TRUE
     * @param bool $compressJs specifies wether to compress the js. Defaults TRUE
     * @param bool $compressCss specifies wether to compress the css. Defaults TRUE
     * @param bool $enableExtJSQuickTips
     * @param string  $extCorePath specifies a path for the ExtCore default NULL (uses the path set in the TYPO3\CMS\Core\Page\PageRenderer)
     * @param string  $extJsPath specifies a path for the ExtJS default NULL (uses the path set in the TYPO3\CMS\Core\Page\PageRenderer)
     * @return string
     * @see template
     * @see TYPO3\CMS\Core\Page\PageRenderer
     */
    public function render($pageTitle = '', $enableJumpToUrl = false, $loadPrototype = false, $loadScriptaculous = false, $scriptaculousModule = '', $loadExtJs = true, $loadExtCore = false, $loadExtJsTheme = true, $extJsAdapter = '', $enableExtJsDebug = false, $concatenate = true, $compressJs = true, $compressCss = true, $enableExtJSQuickTips = true, $extCorePath = null, $extJsPath = null)
    {
        $doc = $this->getDocInstance();

        if ($enableJumpToUrl === true) {
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
        if ($loadPrototype === true) {
            $this->pageRenderer->loadPrototype();
        }
        if ($loadScriptaculous === true) {
            $this->pageRenderer->loadScriptaculous($scriptaculousModule);
        }
        if ($extCorePath !== null) {
            $this->pageRenderer->setExtCorePath($extCorePath);
        }
        if ($extJsPath !== null) {
            $this->pageRenderer->setExtJsPath($extJsPath);
        }
        if ($loadExtJs === true) {
            $this->pageRenderer->loadExtJS(true, $loadExtJsTheme, $extJsAdapter);
            if ($enableExtJsDebug === true) {
                $this->pageRenderer->enableExtJsDebug();
            }
        }
        if ($loadExtCore === true) {
            $this->pageRenderer->loadExtCore();
        }
        if ($enableExtJSQuickTips === true) {
            $this->pageRenderer->enableExtJSQuickTips();
        }

        $this->pageRenderer->addCssFile('sysext/t3skin/extjs/xtheme-t3skin.css');

        $this->renderChildren();

        if ($compressJs === true) {
            $this->pageRenderer->enableCompressJavaScript();
        }
        if ($compressCss === true) {
            $this->pageRenderer->enableCompressCss();
        }
        if ($concatenate === true) {
            $this->pageRenderer->enableConcatenateFiles();
        }
        $output = $doc->startPage($pageTitle);
        $output .= $this->pageRenderer->getBodyContent();
        $output .= $doc->endPage();

        return $output;
    }
}

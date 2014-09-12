<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Dennis Ahrens <dennis.ahrens@fh-hannover.de>
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
 * View helper which allows you to include a JS File.
 * Note: This feature is experimental!
 * Note: You MUST wrap this Helper with <newsletter:Be.moduleContainer>-Tags
 *
 * = Examples =
 *
 * <newsletter:be.moduleContainer pageTitle="foo" enableJumpToUrl="false" enableClickMenu="false" loadPrototype="false" loadScriptaculous="false" scriptaculousModule="someModule,someOtherModule" loadExtJs="true" loadExtJsTheme="false" extJsAdapter="jQuery" enableExtJsDebug="true" addCssFile="{f:uri.resource(path:'styles/backend.css')}" addJsFile="{f:uri.resource('scripts/main.js')}">
 * 	<newsletter:includeJsFile file="foo.js" extKey="blog_example" pathInsideExt="Resources/Public/JavaScript" />
 * </newsletter:be.moduleContainer>
 *
 * @category    ViewHelpers
 * @package     TYPO3
 * @subpackage  tx_newsletter
 * @author      Dennis Ahrens <dennis.ahrens@fh-hannover.de>
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class Tx_Newsletter_ViewHelpers_IncludeJsFolderViewHelper extends Tx_Newsletter_ViewHelpers_AbstractViewHelper
{

    /**
     * Calls addJsFile for each file in the given folder on the Instance of TYPO3\CMS\Core\Page\PageRenderer.
     *
     * @param string $name the file to include
     * @param string $extKey the extension, where the file is located
     * @param string $pathInsideExt the path to the file relative to the ext-folder
     * @param boolean $recursive
     * @return void the files are added to the pagerenderer instance that renders them in the head of the markup
     */
    public function render($name = NULL, $extKey = NULL, $pathInsideExt = 'Resources/Public/JavaScript/', $recursive = FALSE)
    {
        if ($extKey == NULL) {
            $extKey = $this->controllerContext->getRequest()->getControllerExtensionKey();
        }
        $extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extKey);
        if (TYPO3_MODE === 'FE') {
            $extRelPath = substr($extPath, strlen(PATH_site));
        } else {
            $extRelPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($extKey);
        }
        $absFolderPath = $extPath . $pathInsideExt . $name;
        // $files will include all files relative to $pathInsideExt
        if ($recursive === FALSE) {
            $files = \TYPO3\CMS\Core\Utility\GeneralUtility::getFilesInDir($absFolderPath);
            foreach ($files as $hash => $filename) {
                $files[$hash] = $name . $filename;
            }
        } else {
            $files = \TYPO3\CMS\Core\Utility\GeneralUtility::getAllFilesAndFoldersInPath(array(), $absFolderPath, '', 0, 99, '\\.svn');
            foreach ($files as $hash => $absPath) {
                $files[$hash] = str_replace($extPath . $pathInsideExt, '', $absPath);
            }
        }
        foreach ($files as $name) {
            $this->pageRenderer->addJsFile($extRelPath . $pathInsideExt . $name);
        }
    }

}

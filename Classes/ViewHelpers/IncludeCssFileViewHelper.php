<?php

namespace Ecodev\Newsletter\ViewHelpers;

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
 * View helper which allows you to include a CSS File.
 * Note: This feature is experimental!
 * Note: You MUST wrap this Helper with <newsletter:Be.moduleContainer>-Tags
 *
 * = Examples =
 *
 * <newsletter:be.moduleContainer pageTitle="foo">
 * 	<newsletter:includeCssFile name="foo.js" extKey="blog_example" pathInsideExt="Resources/Public/JavaScript" />
 * </newsletter:be.moduleContainer>
 *
 * @author      Dennis Ahrens <dennis.ahrens@fh-hannover.de>
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class IncludeCssFileViewHelper extends AbstractViewHelper
{
    /**
     * Calls addCssFile on the Instance of TYPO3\CMS\Core\Page\PageRenderer.
     *
     * @param string $name the file to include
     * @param string $extKey the extension, where the file is located
     * @param string $pathInsideExt the path to the file relative to the ext-folder
     * @return string the link
     */
    public function render($name = null, $extKey = null, $pathInsideExt = 'Resources/Public/Styles/')
    {
        if ($extKey === null) {
            $extKey = $this->controllerContext->getRequest()->getControllerExtensionKey();
        }

        if (TYPO3_MODE === 'FE') {
            $extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extKey);
            $extRelPath = substr($extPath, strlen(PATH_site));
        } else {
            $extRelPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($extKey);
        }

        $this->pageRenderer->addCssFile($extRelPath . $pathInsideExt . $name);
    }
}

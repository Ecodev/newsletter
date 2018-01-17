<?php

namespace Ecodev\Newsletter\ViewHelpers;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * View helper which allows you to include a JS File.
 * Note: This feature is experimental!
 * Note: You MUST wrap this Helper with <newsletter:Be.moduleContainer>-Tags
 *
 * = Examples =
 *
 * <newsletter:be.moduleContainer pageTitle="foo">
 *    <newsletter:includeJsFile file="foo.js" extKey="blog_example" pathInsideExt="Resources/Public/JavaScript" />
 * </newsletter:be.moduleContainer>
 */
class IncludeJsFileViewHelper extends AbstractViewHelper
{
    /**
     * Calls addJsFile on the Instance of TYPO3\CMS\Core\Page\PageRenderer.
     *
     * @param string $name the file to include
     * @param string $extKey the extension, where the file is located
     * @param string $pathInsideExt the path to the file relative to the ext-folder
     *
     * @return string the link
     */
    public function render($name = null, $extKey = null, $pathInsideExt = 'Resources/Public/JavaScript/')
    {
        if ($extKey == null) {
            $extKey = $this->controllerContext->getRequest()->getControllerExtensionKey();
        }
        if (TYPO3_MODE === 'FE') {
            $extPath = ExtensionManagementUtility::extPath($extKey);
            $extRelPath = mb_substr($extPath, mb_strlen(PATH_site));
        } else {
            $extRelPath = ExtensionManagementUtility::extRelPath($extKey);
        }
        $this->pageRenderer->addJsFile($extRelPath . $pathInsideExt . $name);
    }
}

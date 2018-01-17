<?php

namespace Ecodev\Newsletter\ViewHelpers;

use Ecodev\Newsletter\Exception;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * View helper which allows you to include inline JS code into a module Container.
 * Note: This feature is experimental!
 * Note: You MUST wrap this Helper with <newsletter:Be.moduleContainer>-Tags
 *
 * = Examples =
 *
 * <newsletter:be.moduleContainer pageTitle="foo">
 *    <newsletter:includeExtOnReadyCode file="foo.js" extKey="blog_example" pathInsideExt="Resources/Public/JavaScript" />
 * </newsletter:be.moduleContainer>
 */
class IncludeExtOnReadyFromFileViewHelper extends AbstractViewHelper
{
    /**
     * Calls addJsFile on the Instance of TYPO3\CMS\Core\Page\PageRenderer.
     *
     * @param string $name the file to include
     * @param string $extKey the extension, where the file is located
     * @param string $pathInsideExt the path to the file relative to the ext-folder
     */
    public function render($name = 'extOnReady.js', $extKey = null, $pathInsideExt = 'Resources/Public/JavaScript/')
    {
        if ($extKey == null) {
            $extKey = $this->controllerContext->getRequest()->getControllerExtensionKey();
        }
        $extPath = ExtensionManagementUtility::extPath($extKey);

        $filePath = $extPath . $pathInsideExt . $name;

        if (!file_exists($filePath)) {
            throw new Exception('File not found: ' . $filePath, 1264197781);
        }

        $fileContent = file_get_contents($extPath . $pathInsideExt . $name);

        $this->pageRenderer->addExtOnReadyCode($fileContent);
    }
}

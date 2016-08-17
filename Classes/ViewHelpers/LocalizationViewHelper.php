<?php

namespace Ecodev\Newsletter\ViewHelpers;

/**
 * Make localization files available in JavaScript
 */
class LocalizationViewHelper extends AbstractViewHelper
{
    /**
     * Calls addJsFile on the Instance of TYPO3\CMS\Core\Page\PageRenderer.
     *
     * @param string $name the list of file to include separated by coma
     * @param string $extKey the extension, where the file is located
     * @param string $pathInsideExt the path to the file relative to the ext-folder
     */
    public function render($name = 'locallang.xlf', $extKey = null, $pathInsideExt = 'Resources/Private/Language/')
    {
        $names = explode(',', $name);

        if ($extKey == null) {
            $extKey = $this->controllerContext->getRequest()->getControllerExtensionKey();
        }
        $extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extKey);

        $localizations = [];
        foreach ($names as $name) {
            $filePath = $extPath . $pathInsideExt . $name;
            $localizations = array_merge($localizations, $this->getLocalizations($filePath));
        }

        $localizations = json_encode($localizations);
        $javascript = "Ext.ux.Ecodev.Newsletter.Language = $localizations;";

        $this->pageRenderer->addJsInlineCode($filePath, $javascript);
    }

    /**
     * Returns localization variables within an array
     *
     * @param string $filePath
     * @throws Exception
     * @return array
     */
    protected function getLocalizations($filePath)
    {
        global $LANG;
        global $LOCAL_LANG;

        // Language inclusion
        $LANG->includeLLFile($filePath);
        if (!isset($LOCAL_LANG[$LANG->lang]) || empty($LOCAL_LANG[$LANG->lang])) {
            $lang = 'default';
        } else {
            $lang = $LANG->lang;
        }

        $result = [];
        foreach ($LOCAL_LANG[$lang] as $key => $value) {
            $target = $value[0]['target'];

            // Replace '.' in key because it would break JSON
            $key = str_replace('.', '_', $key);
            $result[$key] = $target;
        }

        return $result;
    }
}

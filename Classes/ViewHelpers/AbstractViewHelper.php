<?php

namespace Ecodev\Newsletter\ViewHelpers;

/**
 * a ViewHelper that holds a pageRenderer Object as instance variable
 */
abstract class AbstractViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Be\AbstractBackendViewHelper
{
    /**
     * @var TYPO3\CMS\Core\Page\PageRenderer
     */
    protected $pageRenderer;

    /**
     * @see typo3/sysext/fluid/Classes/Core/ViewHelper/\TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper#initialize()
     */
    public function initialize()
    {
        $this->pageRenderer = $this->getPageRendererCompatibility();
    }

    private function getPageRendererCompatibility()
    {
        // For TYPO3 7.4 and newer we can use a direct method, for older version 6.2-7.3
        if (is_callable([$this, 'getPageRenderer'])) {
            return $this->getPageRenderer();
        } else {
            return $this->getDocInstance()->getPageRenderer();
        }
    }
}

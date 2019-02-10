<?php

namespace Ecodev\Newsletter\ViewHelpers;

use TYPO3\CMS\Fluid\ViewHelpers\Be\AbstractBackendViewHelper;

/**
 * a ViewHelper that holds a pageRenderer Object as instance variable
 */
abstract class AbstractViewHelper extends AbstractBackendViewHelper
{
    /**
     * @var \TYPO3\CMS\Core\Page\PageRenderer
     */
    protected $pageRenderer;

    /**
     * @see typo3/sysext/fluid/Classes/Core/ViewHelper/\TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper#initialize()
     */
    public function initialize()
    {
        $this->pageRenderer = $this->getPageRenderer();
    }
}

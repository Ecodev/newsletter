<?php

namespace Ecodev\Newsletter\Controller;

use Ecodev\Newsletter\Utility\UriBuilder;

/**
 * The view based backend module controller for the Newsletter package.
 */
class ModuleController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var int
     */
    protected $pageId;

    /**
     * Initializes the controller before invoking an action method.
     */
    protected function initializeAction()
    {
        $this->pageId = intval(\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('id'));
    }

    /**
     * index action for the module controller
     * This will render the HTML needed for ExtJS application
     */
    public function indexAction()
    {
        $pageType = '';
        $record = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('doktype', 'pages', 'uid =' . $this->pageId);
        if (!empty($record['doktype']) && $record['doktype'] == 254) {
            $pageType = 'folder';
        } elseif (!empty($record['doktype'])) {
            $pageType = 'page';
        }

        $configuration = [
            'pageId' => $this->pageId,
            'pageType' => $pageType,
            'emailShowUrl' => UriBuilder::buildFrontendUri($this->pageId, 'Email', 'show'),
        ];

        $this->view->assign('configuration', $configuration);
    }
}

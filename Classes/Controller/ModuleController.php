<?php

namespace Ecodev\Newsletter\Controller;

use Ecodev\Newsletter\Tools;
use Ecodev\Newsletter\Utility\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * The view based backend module controller for the Newsletter package.
 */
class ModuleController extends ActionController
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
        $this->pageId = (int) GeneralUtility::_GP('id');
    }

    /**
     * index action for the module controller
     * This will render the HTML needed for ExtJS application
     */
    public function indexAction()
    {
        $pageType = '';
        $record = Tools::getDatabaseConnection()->exec_SELECTgetSingleRow('doktype', 'pages', 'uid =' . $this->pageId);
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

<?php


namespace Ecodev\Newsletter\Controller;

use Ecodev\Newsletter\Tools;
use GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015
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
 * The view based backend module controller for the Newsletter package.
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ModuleController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * @var int
     */
    protected $pageId;

    /**
     * Initializes the controller before invoking an action method.
     *
     * @return void
     */
    protected function initializeAction()
    {
        $this->pageId = intval(\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('id'));
    }

    /**
     * index action for the module controller
     * This will render the HTML needed for ExtJS application
     *
     * @return void
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

        $configuration = array(
            'pageId' => $this->pageId,
            'pageType' => $pageType,
            'emailShowUrl' => Tools::buildFrontendUri('show', array(), 'Email'),
        );

        $this->view->assign('configuration', $configuration);
    }
}

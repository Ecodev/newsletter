<?php

namespace Ecodev\Newsletter\Controller;

use Ecodev\Newsletter\Domain\Repository\BounceAccountRepository;
use Ecodev\Newsletter\MVC\Controller\ExtDirectActionController;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 * Controller for the BounceAccount object
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class BounceAccountController extends ExtDirectActionController
{
    /**
     * bounceAccountRepository
     *
     * @var Ecodev\\Newsletter\\Domain\\Repository\\BounceAccountRepository
     */
    protected $bounceAccountRepository;

    /**
     * injectBounceAccounRepository
     *
     * @param Ecodev\\Newsletter\\Domain\\Repository\\BounceAccountRepository $bounceAccountRepository
     */
    public function injectBounceAccounRepository(BounceAccountRepository $bounceAccountRepository)
    {
        $this->bounceAccountRepository = $bounceAccountRepository;
    }

    /**
     * Displays all BounceAccounts
     *
     * @return string The rendered list view
     */
    public function listAction()
    {
        $bounceAccounts = $this->bounceAccountRepository->findAll();

        $this->view->setVariablesToRender(['total', 'data', 'success', 'flashMessages']);
        $this->view->setConfiguration([
            'data' => [
                '_descendAll' => self::resolveJsonViewConfiguration(),
            ],
        ]);

        $this->addFlashMessage('Loaded BounceAccounts from Server side.', 'BounceAccounts loaded successfully', \TYPO3\CMS\Core\Messaging\FlashMessage::NOTICE);

        $this->view->assign('total', $bounceAccounts->count());
        $this->view->assign('data', $bounceAccounts);
        $this->view->assign('success', true);
        $this->flushFlashMessages();
    }

    /**
     * Returns a configuration for the JsonView, that describes which fields should be rendered for
     * a BounceAccount record.
     *
     * @return array
     */
    public static function resolveJsonViewConfiguration()
    {
        return [
            '_exposeObjectIdentifier' => true,
            '_only' => [
                'email',
                'server',
                'protocol',
                'username',
            ],
        ];
    }
}

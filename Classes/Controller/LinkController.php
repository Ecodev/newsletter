<?php

namespace Ecodev\Newsletter\Controller;

use Ecodev\Newsletter\Domain\Repository\LinkRepository;
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
 * Controller for the Link object
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class LinkController extends ExtDirectActionController
{
    /**
     * linkRepository
     *
     * @var Ecodev\\Newsletter\\Domain\\Repository\\LinkRepository
     */
    protected $linkRepository;

    /**
     * injectLinkRepository
     * @param Ecodev\\Newsletter\\Domain\\Repository\\LinkRepository $linkRepository
     * @return void
     */
    public function injectLinkRepository(LinkRepository $linkRepository)
    {
        $this->linkRepository = $linkRepository;
    }

    /**
     * Displays all Links
     *
     * @param integer $uidNewsletter
     * @param integer $start
     * @param integer $limit
     * @return string The rendered list view
     */
    public function listAction($uidNewsletter, $start, $limit)
    {
        $links = $this->linkRepository->findAllByNewsletter($uidNewsletter, $start, $limit);

        $this->view->setVariablesToRender(array('total', 'data', 'success', 'flashMessages'));
        $this->view->setConfiguration(array(
            'data' => array(
                '_descendAll' => self::resolveJsonViewConfiguration(),
            ),
        ));

        $this->addFlashMessage('Loaded all Links from Server side.', 'Links loaded successfully', \TYPO3\CMS\Core\Messaging\FlashMessage::NOTICE);

        $this->view->assign('total', $this->linkRepository->getCount($uidNewsletter));
        $this->view->assign('data', $links);
        $this->view->assign('success', true);
        $this->view->assign('flashMessages', $this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush());
    }

    /**
     * Register when a link was clicked and redirect to link's URL.
     * For this method we don't use extbase parameters system to have an URL as short as possible
     */
    public function clickedAction()
    {
        $url = $this->linkRepository->registerClick(@$_REQUEST['n'], @$_REQUEST['l'], @$_REQUEST['p']);

        // Finally redirect to the destination URL
        if ($url) {
            header("Location: $url");
            die();
        } else {
            throw new \TYPO3\CMS\Core\Error\Http\PageNotFoundException('The requested link was not found', 1440490767);
        }
    }

    /**
     * Returns a configuration for the JsonView, that describes which fields should be rendered for
     * a Link record.
     *
     * @return array
     */
    public static function resolveJsonViewConfiguration()
    {
        return array(
            '_exposeObjectIdentifier' => true,
            '_only' => array('url', 'openedCount', 'openedPercentage'),
        );
    }
}

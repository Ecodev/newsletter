<?php

namespace Ecodev\Newsletter\Controller;

use Ecodev\Newsletter\Domain\Repository\RecipientListRepository;
use Ecodev\Newsletter\MVC\Controller\ExtDirectActionController;
use FlashMessage;
use GeneralUtility;

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
 * Controller for the RecipientList object
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class RecipientListController extends ExtDirectActionController
{
    /**
     * recipientListRepository
     *
     * @var Ecodev\\Newsletter\\Domain\\Repository\\RecipientListRepository
     */
    protected $recipientListRepository;

    /**
     * injectRecipientListRepository
     *
     * @param Ecodev\\Newsletter\\Domain\\Repository\\RecipientListRepository $recipientListRepository
     * @return void
     */
    public function injectRecipientListRepository(RecipientListRepository $recipientListRepository)
    {
        $this->recipientListRepository = $recipientListRepository;
    }

    /**
     * Displays all RecipientLists
     *
     * @return string The rendered list view
     */
    public function listAction()
    {
        $recipientLists = $this->recipientListRepository->findAll();

        // We init recipientLists so we can getCount() on them
        foreach ($recipientLists as $recipientList) {
            $recipientList->init();
        }

        $this->view->setVariablesToRender(array('total', 'data', 'success', 'flashMessages'));
        $this->view->setConfiguration(array(
            'data' => array(
                '_descendAll' => self::resolveJsonViewConfiguration(),
            ),
        ));

        $this->addFlashMessage('Loaded RecipientLists from Server side.', 'RecipientLists loaded successfully', \TYPO3\CMS\Core\Messaging\FlashMessage::NOTICE);

        $this->view->assign('total', $recipientLists->count());
        $this->view->assign('data', $recipientLists);
        $this->view->assign('success', true);
        $this->view->assign('flashMessages', $this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush());
    }

    /**
     * Returns the list of recipient for the specified recipientList
     * @param integer $uidRecipientList
     * @param integer $start
     * @param integer $limit
     */
    public function listRecipientAction($uidRecipientList, $start, $limit)
    {
        $recipientLists = $this->recipientListRepository->findByUidInitialized($uidRecipientList);

        // Gather recipient according to defined limits
        $i = 0;
        $recipients = array();
        while ($recipient = $recipientLists->getRecipient()) {
            if ($i++ >= $start) {
                $recipients[] = $recipient;
                if (count($recipients) == $limit) {
                    break;
                }
            }
        }

        $metaData = array(
            'totalProperty' => 'total',
            'successProperty' => 'success',
            'idProperty' => 'uid',
            'root' => 'data',
            'fields' => array(),
        );

        if (count($recipients)) {
            foreach (array_keys(reset($recipients)) as $field) {
                $metaData['fields'][] = array('name' => $field, 'type' => 'string');
            }
        }

        $this->addFlashMessage('Loaded Recipients from Server side.', 'Recipients loaded successfully', \TYPO3\CMS\Core\Messaging\FlashMessage::NOTICE);

        $this->view->assign('metaData', $metaData);
        $this->view->assign('total', $recipientLists->getCount());
        $this->view->assign('data', $recipients);
        $this->view->assign('success', true);
        $this->view->assign('flashMessages', $this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush());
        $this->view->setVariablesToRender(array('metaData', 'total', 'data', 'success', 'flashMessages'));
    }

    /**
     * Export a list of recipient and all their data
     *
     * @param integer $uidRecipientList
     * @param string $authCode
     * @return void
     */
    public function exportAction($uidRecipientList, $authCode)
    {
        // Assert we are using supported formats
        $availableFormats = array('csv', 'xml');
        $format = $this->request->getFormat();
        if (!in_array($format, $availableFormats)) {
            $format = reset($availableFormats);
            $this->request->setFormat($format);
        }

        $recipientList = $this->recipientListRepository->findByUidInitialized($uidRecipientList);
        if (\TYPO3\CMS\Core\Utility\GeneralUtility::stdAuthCode($recipientList->_getCleanProperties()) != $authCode) {
            $this->response->setStatus(401);

            return 'not authorized !';
        }

        $title = $recipientList->getTitle() . '-' . $recipientList->getUid();

        $this->response->setHeader('Content-Type', 'text/' . $format, true);
        $this->response->setHeader('Content-Description', 'File transfer', true);
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $title . '.' . $format . '"', true);

        $recipients = array();
        while ($recipient = $recipientList->getRecipient()) {
            $recipients[] = $recipient;
        }

        $this->view->assign('recipients', $recipients);
        $this->view->assign('title', $title);
        $this->view->assign('fields', array_keys(reset($recipients)));
    }

    /**
     * Returns a configuration for the JsonView, that describes which fields should be rendered for
     * a RecipientList record.
     *
     * @return array
     */
    public static function resolveJsonViewConfiguration()
    {
        return array(
            '_exposeObjectIdentifier' => true,
            '_only' => array(
                'title',
                'plainOnly',
                'lang',
                'type',
                'count',
            ),
        );
    }
}

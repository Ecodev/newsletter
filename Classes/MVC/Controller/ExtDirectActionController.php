<?php

namespace Ecodev\Newsletter\MVC\Controller;

use Ecodev\Newsletter\MVC\View\JsonView;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Dennis Ahrens <dennis.ahrens@fh-hannover.de>
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
 * A Controller used for answering via AJAX speaking JSON
 *
 * @author      Dennis Ahrens <dennis.ahrens@fh-hannover.de>
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class ExtDirectActionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
     * @inject
     */
    protected $persistenceManager;

    /**
     * Injects the PersistenceManager.
     *
     * @param \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager
     */
    public function injectPersistenceManager(\TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * Initializes the View to be a \Ecodev\Newsletter\ExtDirect\View\ExtDirectView that renders json without Template Files.
     */
    public function initializeView(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view)
    {
        if ($this->request->getFormat() === 'extdirect') {
            $this->view = $this->objectManager->get('Ecodev\\Newsletter\\MVC\\View\\ExtDirectView');
            $this->view->setControllerContext($this->controllerContext);
        }
    }

    /**
     * Override parent method to render error message for ExtJS (in JSON).
     * Also append detail about what property failed to error message.
     *
     * @author Adrien Crivelli
     * @return string
     */
    protected function errorAction()
    {
        $message = parent::errorAction();

        // Append detail of properties if available
        // Message layout is not optimal, but at least we avoid code duplication
        foreach ($this->argumentsMappingResults->getErrors() as $error) {
            if ($error instanceof \TYPO3\CMS\Extbase\Validation\PropertyError) {
                foreach ($error->getErrors() as $subError) {
                    $message .= 'Error:   ' . $subError->getMessage() . PHP_EOL;
                }
            }
        }
        if ($this->view instanceof JsonView) {
            $this->view->setVariablesToRender(array('flashMessages', 'error', 'success'));
            $this->view->assign('flashMessages', $this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush());
            $this->view->assign('error', $message);
            $this->view->assign('success', false);
        }
    }

    /**
     * Creates a Message object and adds it to the FlashMessageQueue.
     *
     * For TYPO3 6.1 backward compatibility, replicate here the helper function from 6.2
     *
     * @param string $messageBody The message
     * @param string $messageTitle Optional message title
     * @param int $severity Optional severity, must be one of \TYPO3\CMS\Core\Messaging\FlashMessage constants
     * @param bool $storeInSession Optional, defines whether the message should be stored in the session (default) or not
     * @throws \InvalidArgumentException if the message body is no string
     * @see \TYPO3\CMS\Core\Messaging\FlashMessage
     * @api
     */
    public function addFlashMessage($messageBody, $messageTitle = '', $severity = \TYPO3\CMS\Core\Messaging\AbstractMessage::OK, $storeInSession = true)
    {
        if (!is_string($messageBody)) {
            throw new \InvalidArgumentException('The message body must be of type string, "' . gettype($messageBody) . '" given.', 1243258395);
        }
        /* @var \TYPO3\CMS\Core\Messaging\FlashMessage $flashMessage */
        $flashMessage = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
                        'TYPO3\\CMS\\Core\\Messaging\\FlashMessage', $messageBody, $messageTitle, $severity, $storeInSession
        );
        $this->controllerContext->getFlashMessageQueue()->enqueue($flashMessage);
    }

    /**
     * Translate key
     * @param string $key
     * @param array $args
     * @return string
     */
    protected function translate($key, array $args = array())
    {
        return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($key, 'newsletter', $args);
    }

    /**
     * Flush flashMessages into view
     */
    protected function flushFlashMessages()
    {
        $this->view->assign('flashMessages', $this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush());
    }
}

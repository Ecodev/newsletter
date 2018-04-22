<?php

namespace Ecodev\Newsletter\MVC\Controller;

use Ecodev\Newsletter\MVC\View\ExtDirectView;
use Ecodev\Newsletter\MVC\View\JsonView;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Validation\PropertyError;

/**
 * A Controller used for answering via AJAX speaking JSON
 */
class ExtDirectActionController extends ActionController
{
    /**
     * @inject
     * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * Injects the PersistenceManager.
     *
     * @param PersistenceManagerInterface $persistenceManager
     */
    public function injectPersistenceManager(PersistenceManagerInterface $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * Initializes the View to be a \Ecodev\Newsletter\ExtDirect\View\ExtDirectView that renders json without Template Files.
     *
     * @param ViewInterface $view
     */
    public function initializeView(ViewInterface $view)
    {
        if ($this->request->getFormat() === 'extdirect') {
            $this->view = $this->objectManager->get(ExtDirectView::class);
            $this->view->setControllerContext($this->controllerContext);
        }
    }

    /**
     * Override parent method to render error message for ExtJS (in JSON).
     * Also append detail about what property failed to error message.
     */
    protected function errorAction()
    {
        $message = parent::errorAction();

        // Append detail of properties if available
        // Message layout is not optimal, but at least we avoid code duplication
        foreach ($this->argumentsMappingResults->getErrors() as $error) {
            if ($error instanceof PropertyError) {
                foreach ($error->getErrors() as $subError) {
                    $message .= 'Error:   ' . $subError->getMessage() . PHP_EOL;
                }
            }
        }
        if ($this->view instanceof JsonView) {
            $this->view->setVariablesToRender(['flashMessages', 'error', 'success']);
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
     *
     * @throws \InvalidArgumentException if the message body is no string
     * @see \TYPO3\CMS\Core\Messaging\FlashMessage
     * @api
     */
    public function addFlashMessage($messageBody, $messageTitle = '', $severity = AbstractMessage::OK, $storeInSession = true)
    {
        if (!is_string($messageBody)) {
            throw new \InvalidArgumentException('The message body must be of type string, "' . gettype($messageBody) . '" given.', 1243258395);
        }
        /* @var FlashMessage $flashMessage */
        $flashMessage = GeneralUtility::makeInstance(
            FlashMessage::class, $messageBody, $messageTitle, $severity, $storeInSession
        );
        $this->controllerContext->getFlashMessageQueue()->enqueue($flashMessage);
    }

    /**
     * Translate key
     *
     * @param string $key
     * @param array $args
     *
     * @return string
     */
    protected function translate($key, array $args = [])
    {
        return LocalizationUtility::translate($key, 'newsletter', $args);
    }

    /**
     * Flush flashMessages into view
     */
    protected function flushFlashMessages()
    {
        $this->view->assign('flashMessages', $this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush());
    }
}

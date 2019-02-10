<?php

namespace Ecodev\Newsletter\MVC\Controller;

use Ecodev\Newsletter\MVC\View\ExtDirectView;
use Ecodev\Newsletter\MVC\View\JsonView;
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

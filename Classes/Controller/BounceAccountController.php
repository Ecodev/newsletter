<?php

namespace Ecodev\Newsletter\Controller;

use Ecodev\Newsletter\Domain\Repository\BounceAccountRepository;
use Ecodev\Newsletter\MVC\Controller\ExtDirectActionController;

/**
 * Controller for the BounceAccount object
 */
class BounceAccountController extends ExtDirectActionController
{
    /**
     * bounceAccountRepository
     *
     * @var Ecodev\Newsletter\Domain\Repository\BounceAccountRepository
     */
    protected $bounceAccountRepository;

    /**
     * injectBounceAccounRepository
     *
     * @param Ecodev\Newsletter\Domain\Repository\BounceAccountRepository $bounceAccountRepository
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

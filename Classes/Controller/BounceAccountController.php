<?php

namespace Ecodev\Newsletter\Controller;

use Ecodev\Newsletter\Domain\Repository\BounceAccountRepository;
use Ecodev\Newsletter\MVC\Controller\ExtDirectActionController;
use TYPO3\CMS\Core\Messaging\FlashMessage;

/**
 * Controller for the BounceAccount object
 */
class BounceAccountController extends ExtDirectActionController
{
    /**
     * bounceAccountRepository
     *
     * @var BounceAccountRepository
     */
    protected $bounceAccountRepository;

    /**
     * injectBounceAccounRepository
     *
     * @param BounceAccountRepository $bounceAccountRepository
     */
    public function injectBounceAccountRepository(BounceAccountRepository $bounceAccountRepository)
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

        $this->addFlashMessage('Loaded BounceAccounts from Server side.', 'BounceAccounts loaded successfully', FlashMessage::NOTICE);

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

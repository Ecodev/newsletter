<?php

namespace Ecodev\Newsletter\Tests\Unit\Controller;

use Ecodev\Newsletter\Controller\BounceAccountController;
use Ecodev\Newsletter\Domain\Repository\BounceAccountRepository;
use Ecodev\Newsletter\MVC\View\ExtDirectView;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Test case for class Ecodev\Newsletter\Controller\BounceAccountController.
 */
class BounceAccountControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var BounceAccountController
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = $this->getMock(BounceAccountController::class, ['redirect', 'forward', 'addFlashMessage', 'translate', 'flushFlashMessages'], [], '', false);
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function listActionFetchesAllBounceAccountsFromRepositoryAndAssignsThemToView()
    {
        $allBounceAccounts = $this->getMock(ObjectStorage::class, [], [], '', false);

        $bounceAccountRepository = $this->getMock(BounceAccountRepository::class, ['findAll'], [], '', false);
        $bounceAccountRepository->expects($this->once())->method('findAll')->will($this->returnValue($allBounceAccounts));
        $this->inject($this->subject, 'bounceAccountRepository', $bounceAccountRepository);

        $view = $this->getMock(ExtDirectView::class, ['assign']);
        $view->expects($this->at(0))->method('assign')->with('total', count($allBounceAccounts));
        $view->expects($this->at(1))->method('assign')->with('data', $allBounceAccounts);
        $view->expects($this->at(2))->method('assign')->with('success', true);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }
}

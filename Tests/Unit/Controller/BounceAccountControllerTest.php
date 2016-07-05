<?php

namespace Ecodev\Newsletter\Tests\Unit\Controller;

/**
 * Test case for class Ecodev\Newsletter\Controller\BounceAccountController.
 */
class BounceAccountControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Ecodev\Newsletter\Controller\BounceAccountController
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = $this->getMock(\Ecodev\Newsletter\Controller\BounceAccountController::class, ['redirect', 'forward', 'addFlashMessage', 'translate', 'flushFlashMessages'], [], '', false);
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
        $allBounceAccounts = $this->getMock(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class, [], [], '', false);

        $bounceAccountRepository = $this->getMock(\Ecodev\Newsletter\Domain\Repository\BounceAccountRepository::class, ['findAll'], [], '', false);
        $bounceAccountRepository->expects($this->once())->method('findAll')->will($this->returnValue($allBounceAccounts));
        $this->inject($this->subject, 'bounceAccountRepository', $bounceAccountRepository);

        $view = $this->getMock(\Ecodev\Newsletter\MVC\View\ExtDirectView::class, ['assign']);
        $view->expects($this->at(0))->method('assign')->with('total', count($allBounceAccounts));
        $view->expects($this->at(1))->method('assign')->with('data', $allBounceAccounts);
        $view->expects($this->at(2))->method('assign')->with('success', true);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }
}

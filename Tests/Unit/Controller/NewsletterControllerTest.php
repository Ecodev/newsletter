<?php

namespace Ecodev\Newsletter\Tests\Unit\Controller;

use Ecodev\Newsletter\Controller\NewsletterController;
use Ecodev\Newsletter\Domain\Model\Newsletter;
use Ecodev\Newsletter\Domain\Model\RecipientList\CsvList;
use Ecodev\Newsletter\Domain\Repository\NewsletterRepository;
use Ecodev\Newsletter\MVC\View\ExtDirectView;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Test case for class Ecodev\Newsletter\Controller\NewsletterController.
 */
class NewsletterControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var NewsletterController
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = $this->getMock(NewsletterController::class, ['redirect', 'forward', 'addFlashMessage', 'translate', 'flushFlashMessages'], [], '', false);
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function listActionFetchesAllNewslettersFromRepositoryAndAssignsThemToView()
    {
        $allNewsletters = $this->getMock(ObjectStorage::class, [], [], '', false);

        $newsletterRepository = $this->getMock(NewsletterRepository::class, ['findAll'], [], '', false);
        $newsletterRepository->expects($this->once())->method('findAll')->will($this->returnValue($allNewsletters));
        $this->inject($this->subject, 'newsletterRepository', $newsletterRepository);

        $view = $this->getMock(ExtDirectView::class, ['assign']);
        $view->expects($this->at(0))->method('assign')->with('total', count($allNewsletters));
        $view->expects($this->at(1))->method('assign')->with('data', $allNewsletters);
        $view->expects($this->at(2))->method('assign')->with('success', true);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenNewsletterToNewsletterRepository()
    {
        $newsletter = $this->getMock(Newsletter::class, ['getValidatedContent'], [], '', false);
        $newsletter->expects($this->once())->method('getValidatedContent')->will($this->returnValue([
            'content' => 'some content',
            'errors' => [],
            'warnings' => [],
            'infos' => [],
        ]));
        $recipientList = new CsvList();
        $newsletter->setRecipientList($recipientList);

        $persistenceManager = $this->getMock(PersistenceManager::class, ['persistAll'], [], '', false);
        $persistenceManager->expects($this->once())->method('persistAll')->will($this->returnValue(null));
        $this->inject($this->subject, 'persistenceManager', $persistenceManager);

        $view = $this->getMock(ExtDirectView::class);
        $this->inject($this->subject, 'view', $view);

        $newsletterRepository = $this->getMock(NewsletterRepository::class, ['add'], [], '', false);
        $newsletterRepository->expects($this->once())->method('add')->with($newsletter);
        $this->inject($this->subject, 'newsletterRepository', $newsletterRepository);

        $this->subject->createAction($newsletter);
    }
}

<?php
namespace Ecodev\Newsletter\Tests\Unit\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015
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
 ***************************************************************/

/**
 * Test case for class Ecodev\Newsletter\Controller\NewsletterController.
 *
 */
class NewsletterControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var \Ecodev\Newsletter\Controller\NewsletterController
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = $this->getMock('Ecodev\\Newsletter\\Controller\\NewsletterController', array('redirect', 'forward', 'addFlashMessage'), array(), '', false);
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
        $allNewsletters = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);

        $newsletterRepository = $this->getMock('Ecodev\\Newsletter\\Domain\\Repository\\NewsletterRepository', array('findAll'), array(), '', false);
        $newsletterRepository->expects($this->once())->method('findAll')->will($this->returnValue($allNewsletters));
        $this->inject($this->subject, 'newsletterRepository', $newsletterRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assign')->with('newsletters', $allNewsletters);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenNewsletterToView()
    {
        $newsletter = new \Ecodev\Newsletter\Domain\Model\Newsletter();

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);
        $view->expects($this->once())->method('assign')->with('newsletter', $newsletter);

        $this->subject->showAction($newsletter);
    }

    /**
     * @test
     */
    public function newActionAssignsTheGivenNewsletterToView()
    {
        $newsletter = new \Ecodev\Newsletter\Domain\Model\Newsletter();

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assign')->with('newNewsletter', $newsletter);
        $this->inject($this->subject, 'view', $view);

        $this->subject->newAction($newsletter);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenNewsletterToNewsletterRepository()
    {
        $newsletter = new \Ecodev\Newsletter\Domain\Model\Newsletter();

        $newsletterRepository = $this->getMock('Ecodev\\Newsletter\\Domain\\Repository\\NewsletterRepository', array('add'), array(), '', false);
        $newsletterRepository->expects($this->once())->method('add')->with($newsletter);
        $this->inject($this->subject, 'newsletterRepository', $newsletterRepository);

        $this->subject->createAction($newsletter);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenNewsletterToView()
    {
        $newsletter = new \Ecodev\Newsletter\Domain\Model\Newsletter();

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);
        $view->expects($this->once())->method('assign')->with('newsletter', $newsletter);

        $this->subject->editAction($newsletter);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenNewsletterInNewsletterRepository()
    {
        $newsletter = new \Ecodev\Newsletter\Domain\Model\Newsletter();

        $newsletterRepository = $this->getMock('Ecodev\\Newsletter\\Domain\\Repository\\NewsletterRepository', array('update'), array(), '', false);
        $newsletterRepository->expects($this->once())->method('update')->with($newsletter);
        $this->inject($this->subject, 'newsletterRepository', $newsletterRepository);

        $this->subject->updateAction($newsletter);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenNewsletterFromNewsletterRepository()
    {
        $newsletter = new \Ecodev\Newsletter\Domain\Model\Newsletter();

        $newsletterRepository = $this->getMock('Ecodev\\Newsletter\\Domain\\Repository\\NewsletterRepository', array('remove'), array(), '', false);
        $newsletterRepository->expects($this->once())->method('remove')->with($newsletter);
        $this->inject($this->subject, 'newsletterRepository', $newsletterRepository);

        $this->subject->deleteAction($newsletter);
    }
}

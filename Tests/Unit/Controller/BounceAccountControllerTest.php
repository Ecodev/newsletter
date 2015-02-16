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
 * Test case for class Ecodev\Newsletter\Controller\BounceAccountController.
 *
 */
class BounceAccountControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var \Ecodev\Newsletter\Controller\BounceAccountController
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = $this->getMock('Ecodev\\Newsletter\\Controller\\BounceAccountController', array('redirect', 'forward', 'addFlashMessage'), array(), '', false);
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
        $allBounceAccounts = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);

        $bounceAccountRepository = $this->getMock('Ecodev\\Newsletter\\Domain\\Repository\\BounceAccountRepository', array('findAll'), array(), '', false);
        $bounceAccountRepository->expects($this->once())->method('findAll')->will($this->returnValue($allBounceAccounts));
        $this->inject($this->subject, 'bounceAccountRepository', $bounceAccountRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assign')->with('bounceAccounts', $allBounceAccounts);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenBounceAccountToView()
    {
        $bounceAccount = new \Ecodev\Newsletter\Domain\Model\BounceAccount();

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);
        $view->expects($this->once())->method('assign')->with('bounceAccount', $bounceAccount);

        $this->subject->showAction($bounceAccount);
    }
}

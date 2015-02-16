<?php

namespace Ecodev\Newsletter\Tests\Unit\Domain\Model;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015
 *
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
 * Test case for class \Ecodev\Newsletter\Domain\Model\BounceAccount.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class BounceAccountTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Ecodev\Newsletter\Domain\Model\BounceAccount
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = new \Ecodev\Newsletter\Domain\Model\BounceAccount();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getEmailReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getEmail());
    }

    /**
     * @test
     */
    public function setEmailForStringSetsEmail()
    {
        $this->subject->setEmail('Conceived at T3CON10');
        $this->assertAttributeEquals('Conceived at T3CON10', 'email', $this->subject);
    }

    /**
     * @test
     */
    public function getServerReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getServer());
    }

    /**
     * @test
     */
    public function setServerForStringSetsServer()
    {
        $this->subject->setServer('Conceived at T3CON10');
        $this->assertAttributeEquals('Conceived at T3CON10', 'server', $this->subject);
    }

    /**
     * @test
     */
    public function getProtocolReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getProtocol());
    }

    /**
     * @test
     */
    public function setProtocolForStringSetsProtocol()
    {
        $this->subject->setProtocol('Conceived at T3CON10');
        $this->assertAttributeEquals('Conceived at T3CON10', 'protocol', $this->subject);
    }

    /**
     * @test
     */
    public function getUsernameReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getUsername());
    }

    /**
     * @test
     */
    public function setUsernameForStringSetsUsername()
    {
        $this->subject->setUsername('Conceived at T3CON10');
        $this->assertAttributeEquals('Conceived at T3CON10', 'username', $this->subject);
    }

    /**
     * @test
     */
    public function getPasswordReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getPassword());
    }

    /**
     * @test
     */
    public function setPasswordForStringSetsPassword()
    {
        $this->subject->setPassword('Conceived at T3CON10');
        $this->assertAttributeEquals('Conceived at T3CON10', 'password', $this->subject);
    }
}

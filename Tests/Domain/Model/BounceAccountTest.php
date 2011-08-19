<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2011 
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
 * Testcase for class Tx_Newsletter_Domain_Model_BounceAccount.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Newsletter
 * 
 */
class Tx_Newsletter_Domain_Model_BounceAccountTest extends Tx_Extbase_Tests_Unit_BaseTestCase {
	/**
	 * @var Tx_Newsletter_Domain_Model_BounceAccount
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new Tx_Newsletter_Domain_Model_BounceAccount();
	}

	public function tearDown() {
		unset($this->fixture);
	}
	
	
	/**
	 * @test
	 */
	public function getEmailReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setEmailForStringSetsEmail() { 
		$this->fixture->setEmail('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getEmail()
		);
	}
	
	/**
	 * @test
	 */
	public function getServerReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setServerForStringSetsServer() { 
		$this->fixture->setServer('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getServer()
		);
	}
	
	/**
	 * @test
	 */
	public function getProtocolReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setProtocolForStringSetsProtocol() { 
		$this->fixture->setProtocol('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getProtocol()
		);
	}
	
	/**
	 * @test
	 */
	public function getUsernameReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setUsernameForStringSetsUsername() { 
		$this->fixture->setUsername('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getUsername()
		);
	}
	
	/**
	 * @test
	 */
	public function getPasswordReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setPasswordForStringSetsPassword() { 
		$this->fixture->setPassword('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getPassword()
		);
	}
	
}

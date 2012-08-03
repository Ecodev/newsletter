<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2012
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
 * Testcase for class Tx_Newsletter_Domain_Model_Link.
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Newsletter_Domain_Model_LinkTest extends Tx_Extbase_Tests_Unit_BaseTestCase {
	/**
	 * @var Tx_Newsletter_Domain_Model_Link
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new Tx_Newsletter_Domain_Model_Link();
	}

	public function tearDown() {
		unset($this->fixture);
	}


	/**
	 * @test
	 */
	public function getTypeReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setTypeForStringSetsType() {
		$this->fixture->setType('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getType()
		);
	}

	/**
	 * @test
	 */
	public function getUrlReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setUrlForStringSetsUrl() {
		$this->fixture->setUrl('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getUrl()
		);
	}

	/**
	 * @test
	 */
	public function getNewsletterReturnsInitialValueForTx_Newsletter_Domain_Model_Newsletter() {
		$this->assertEquals(
			NULL,
			$this->fixture->getNewsletter()
		);
	}

	/**
	 * @test
	 */
	public function setNewsletterForTx_Newsletter_Domain_Model_NewsletterSetsNewsletter() {
		$dummyObject = new Tx_Newsletter_Domain_Model_Newsletter();
		$this->fixture->setNewsletter($dummyObject);

		$this->assertSame(
			$dummyObject,
			$this->fixture->getNewsletter()
		);
	}

}

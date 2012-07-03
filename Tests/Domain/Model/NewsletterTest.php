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
 * Testcase for class Tx_Newsletter_Domain_Model_Newsletter.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Newsletter
 *
 */
class Tx_Newsletter_Domain_Model_NewsletterTest extends Tx_Extbase_Tests_Unit_BaseTestCase {
	/**
	 * @var Tx_Newsletter_Domain_Model_Newsletter
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new Tx_Newsletter_Domain_Model_Newsletter();
	}

	public function tearDown() {
		unset($this->fixture);
	}


	/**
	 * @test
	 */
	public function getPlannedTimeReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->fixture->getPlannedTime()
		);
	}

	/**
	 * @test
	 */
	public function setPlannedTimeForIntegerSetsPlannedTime() {
		$this->fixture->setPlannedTime(12);

		$this->assertSame(
			12,
			$this->fixture->getPlannedTime()
		);
	}

	/**
	 * @test
	 */
	public function getBeginTimeReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setBeginTimeForStringSetsBeginTime() {
		$this->fixture->setBeginTime('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getBeginTime()
		);
	}

	/**
	 * @test
	 */
	public function getEndTimeReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setEndTimeForStringSetsEndTime() {
		$this->fixture->setEndTime('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getEndTime()
		);
	}

	/**
	 * @test
	 */
	public function getRepetitionReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->fixture->getRepetition()
		);
	}

	/**
	 * @test
	 */
	public function setRepetitionForIntegerSetsRepeat() {
		$this->fixture->setRepetition(12);

		$this->assertSame(
			12,
			$this->fixture->getRepetition()
		);
	}

	/**
	 * @test
	 */
	public function getPlainConverterReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setPlainConverterForStringSetsPlainConverter() {
		$this->fixture->setPlainConverter('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getPlainConverter()
		);
	}

	/**
	 * @test
	 */
	public function getIsTestReturnsInitialValueForBoolean() {
		$this->assertSame(
			TRUE,
			$this->fixture->getIsTest()
		);
	}

	/**
	 * @test
	 */
	public function setIsTestForBooleanSetsIsTest() {
		$this->fixture->setIsTest(TRUE);

		$this->assertSame(
			TRUE,
			$this->fixture->getIsTest()
		);
	}

	/**
	 * @test
	 */
	public function getAttachmentsReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setAttachmentsForStringSetsAttachments() {
		$this->fixture->setAttachments('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getAttachments()
		);
	}

	/**
	 * @test
	 */
	public function getSenderNameReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setSenderNameForStringSetsSenderName() {
		$this->fixture->setSenderName('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getSenderName()
		);
	}

	/**
	 * @test
	 */
	public function getSenderEmailReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setSenderEmailForStringSetsSenderEmail() {
		$this->fixture->setSenderEmail('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getSenderEmail()
		);
	}

	/**
	 * @test
	 */
	public function getInjectOpenSpyReturnsInitialValueForBoolean() {
		$this->assertSame(
			TRUE,
			$this->fixture->getInjectOpenSpy()
		);
	}

	/**
	 * @test
	 */
	public function setInjectOpenSpyForBooleanSetsInjectOpenSpy() {
		$this->fixture->setInjectOpenSpy(TRUE);

		$this->assertSame(
			TRUE,
			$this->fixture->getInjectOpenSpy()
		);
	}

	/**
	 * @test
	 */
	public function getInjectLinksSpyReturnsInitialValueForBoolean() {
		$this->assertSame(
			TRUE,
			$this->fixture->getInjectLinksSpy()
		);
	}

	/**
	 * @test
	 */
	public function setInjectLinksSpyForBooleanSetsInjectLinksSpy() {
		$this->fixture->setInjectLinksSpy(TRUE);

		$this->assertSame(
			TRUE,
			$this->fixture->getInjectLinksSpy()
		);
	}

	/**
	 * @test
	 */
	public function getBounceAccountReturnsInitialValueForTx_Newsletter_Domain_Model_BounceAccount() {
		$this->assertEquals(
			NULL,
			$this->fixture->getBounceAccount()
		);
	}

	/**
	 * @test
	 */
	public function setBounceAccountForTx_Newsletter_Domain_Model_BounceAccountSetsBounceAccount() {
		$dummyObject = new Tx_Newsletter_Domain_Model_BounceAccount();
		$this->fixture->setBounceAccount($dummyObject);

		$this->assertSame(
			$dummyObject,
			$this->fixture->getBounceAccount()
		);
	}

	/**
	 * @test
	 */
	public function getRecipientListReturnsInitialValueForTx_Newsletter_Domain_Model_RecipientList() {
		$this->assertEquals(
			NULL,
			$this->fixture->getRecipientList()
		);
	}

	/**
	 * @test
	 */
	public function setRecipientListForTx_Newsletter_Domain_Model_RecipientListSetsRecipientList() {
		$dummyObject = new Tx_Newsletter_Domain_Model_RecipientList();
		$this->fixture->setRecipientList($dummyObject);

		$this->assertSame(
			$dummyObject,
			$this->fixture->getRecipientList()
		);
	}

}

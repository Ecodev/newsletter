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
 * Testcase for class Tx_Newsletter_Domain_Model_RecipientList.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Newsletter
 * 
 */
class Tx_Newsletter_Domain_Model_RecipientListTest extends Tx_Extbase_Tests_Unit_BaseTestCase {
	/**
	 * @var Tx_Newsletter_Domain_Model_RecipientList
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new Tx_Newsletter_Domain_Model_RecipientList();
	}

	public function tearDown() {
		unset($this->fixture);
	}
	
	
	/**
	 * @test
	 */
	public function getTitleReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setTitleForStringSetsTitle() { 
		$this->fixture->setTitle('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getTitle()
		);
	}
	
	/**
	 * @test
	 */
	public function getPlainOnlyReturnsInitialValueForBoolean() { 
		$this->assertSame(
			TRUE,
			$this->fixture->getPlainOnly()
		);
	}

	/**
	 * @test
	 */
	public function setPlainOnlyForBooleanSetsPlainOnly() { 
		$this->fixture->setPlainOnly(TRUE);

		$this->assertSame(
			TRUE,
			$this->fixture->getPlainOnly()
		);
	}
	
	/**
	 * @test
	 */
	public function getLangReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setLangForStringSetsLang() { 
		$this->fixture->setLang('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getLang()
		);
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
	public function getBeUsersReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setBeUsersForStringSetsBeUsers() { 
		$this->fixture->setBeUsers('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getBeUsers()
		);
	}
	
	/**
	 * @test
	 */
	public function getFeGroupsReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setFeGroupsForStringSetsFeGroups() { 
		$this->fixture->setFeGroups('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getFeGroups()
		);
	}
	
	/**
	 * @test
	 */
	public function getFePagesReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setFePagesForStringSetsFePages() { 
		$this->fixture->setFePages('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getFePages()
		);
	}
	
	/**
	 * @test
	 */
	public function getTtAddressReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setTtAddressForStringSetsTtAddress() { 
		$this->fixture->setTtAddress('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getTtAddress()
		);
	}
	
	/**
	 * @test
	 */
	public function getCsvUrlReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setCsvUrlForStringSetsCsvUrl() { 
		$this->fixture->setCsvUrl('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getCsvUrl()
		);
	}
	
	/**
	 * @test
	 */
	public function getCsvSeparatorReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setCsvSeparatorForStringSetsCsvSeparator() { 
		$this->fixture->setCsvSeparator('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getCsvSeparator()
		);
	}
	
	/**
	 * @test
	 */
	public function getCsvFieldsReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setCsvFieldsForStringSetsCsvFields() { 
		$this->fixture->setCsvFields('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getCsvFields()
		);
	}
	
	/**
	 * @test
	 */
	public function getCsvFilenameReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setCsvFilenameForStringSetsCsvFilename() { 
		$this->fixture->setCsvFilename('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getCsvFilename()
		);
	}
	
	/**
	 * @test
	 */
	public function getCsvValuesReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setCsvValuesForStringSetsCsvValues() { 
		$this->fixture->setCsvValues('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getCsvValues()
		);
	}
	
	/**
	 * @test
	 */
	public function getSqlReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setSqlForStringSetsSql() { 
		$this->fixture->setSql('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getSql()
		);
	}
	
	/**
	 * @test
	 */
	public function getHtmlFileReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setHtmlFileForStringSetsHtmlFile() { 
		$this->fixture->setHtmlFile('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getHtmlFile()
		);
	}
	
	/**
	 * @test
	 */
	public function getHtmlFetchTypeReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setHtmlFetchTypeForStringSetsHtmlFetchType() { 
		$this->fixture->setHtmlFetchType('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getHtmlFetchType()
		);
	}
	
	/**
	 * @test
	 */
	public function getCalculatedRecipientsReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setCalculatedRecipientsForStringSetsCalculatedRecipients() { 
		$this->fixture->setCalculatedRecipients('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getCalculatedRecipients()
		);
	}
	
	/**
	 * @test
	 */
	public function getConfirmedRecipientsReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setConfirmedRecipientsForStringSetsConfirmedRecipients() { 
		$this->fixture->setConfirmedRecipients('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getConfirmedRecipients()
		);
	}
	
}
?>
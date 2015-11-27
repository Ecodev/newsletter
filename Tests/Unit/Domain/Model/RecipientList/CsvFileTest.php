<?php

namespace Ecodev\Newsletter\Tests\Unit\Domain\Model\RecipientList;

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
 * Test case for class \Ecodev\Newsletter\Domain\Model\RecipientList\CsvFile.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CsvFileTest extends AbstractRecipientList
{
    protected function setUp()
    {
        $this->subject = new \Ecodev\Newsletter\Domain\Model\RecipientList\CsvFile();
    }

    /**
     * @test
     */
    public function getCsvSeparatorReturnsInitialValueForString()
    {
        $this->assertSame(',', $this->subject->getCsvSeparator());
    }

    /**
     * @test
     */
    public function setCsvSeparatorForStringSetsCsvSeparator()
    {
        $this->subject->setCsvSeparator('Conceived at T3CON10');
        $this->assertAttributeSame('Conceived at T3CON10', 'csvSeparator', $this->subject);
    }

    /**
     * @test
     */
    public function getCsvFieldsReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getCsvFields());
    }

    /**
     * @test
     */
    public function setCsvFieldsForStringSetsCsvFields()
    {
        $this->subject->setCsvFields('Conceived at T3CON10');
        $this->assertAttributeSame('Conceived at T3CON10', 'csvFields', $this->subject);
    }

    /**
     * @test
     */
    public function getCsvFilenameReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getCsvFilename());
    }

    /**
     * @test
     */
    public function setCsvFilenameForStringSetsCsvFilename()
    {
        $this->subject->setCsvFilename('Conceived at T3CON10');
        $this->assertAttributeSame('Conceived at T3CON10', 'csvFilename', $this->subject);
    }

    protected function prepareDataForEnumeration()
    {
        $this->subject = $this->getMock('Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\CsvFile', array('getPathname'), array(), '', false);
        $this->subject->expects($this->once())->method('getPathname')->will($this->returnValue(__DIR__));
        $this->subject->setCsvFilename('data.csv');
    }

    /**
     * @test
     */
    public function canEnumerateRecipients()
    {
        $this->prepareDataForEnumeration();
        $this->subject->setCsvFields('email,name,some_flags');
        $this->subject->init();
        $this->assertSame(2, $this->subject->getCount());

        $recipient1 = array(
            'email' => 'john@example.com',
            'name' => 'John',
            'some_flags' => '1',
            'plain_only' => false,
        );

        $recipient2 = array(
            'email' => 'bob@example.com',
            'name' => 'Roger',
            'some_flags' => '0',
            'plain_only' => false,
        );

        $this->assertSame($recipient1, $this->subject->getRecipient());
        $this->assertSame($recipient2, $this->subject->getRecipient());
        $this->assertFalse($this->subject->getRecipient());
    }
}

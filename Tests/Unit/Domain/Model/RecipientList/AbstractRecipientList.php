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
 * Test case for class \Ecodev\Newsletter\Domain\Model\CsvList.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
abstract class AbstractRecipientList extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Ecodev\Newsletter\Domain\Model\RecipientList
     */
    protected $subject = null;

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle()
    {
        $this->subject->setTitle('Conceived at T3CON10');
        $this->assertAttributeEquals('Conceived at T3CON10', 'title', $this->subject);
    }

    /**
     * @test
     */
    public function getPlainOnlyReturnsInitialValueForBoolean()
    {
        $this->assertSame(
                false, $this->subject->getPlainOnly()
        );
        $this->assertSame(
                false, $this->subject->isPlainOnly()
        );
    }

    /**
     * @test
     */
    public function setPlainOnlyForBooleanSetsPlainOnly()
    {
        $this->subject->setPlainOnly(true);

        $this->assertAttributeEquals(
                true, 'plainOnly', $this->subject
        );
    }

    /**
     * @test
     */
    public function getLangReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getLang());
    }

    /**
     * @test
     */
    public function setLangForStringSetsLang()
    {
        $this->subject->setLang('Conceived at T3CON10');
        $this->assertAttributeEquals('Conceived at T3CON10', 'lang', $this->subject);
    }

    /**
     * @test
     */
    public function getTypeReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getType());
    }

    /**
     * @test
     */
    public function setTypeForStringSetsType()
    {
        $this->subject->setType('Conceived at T3CON10');
        $this->assertAttributeEquals('Conceived at T3CON10', 'type', $this->subject);
    }
}

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
 * Test case for class \Ecodev\Newsletter\Domain\Model\Sql.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class SqlTest extends AbstractRecipientList
{
    protected function setUp()
    {
        $this->subject = new \Ecodev\Newsletter\Domain\Model\RecipientList\Sql();
    }

    /**
     * @test
     */
    public function getSqlStatementReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getSqlStatement());
    }

    /**
     * @test
     */
    public function setSqlStatementForStringSetsSqlStatement()
    {
        $this->subject->setSqlStatement('Conceived at T3CON10');
        $this->assertAttributeSame('Conceived at T3CON10', 'sqlStatement', $this->subject);
    }

    /**
     * @test
     */
    public function getSqlRegisterBounceReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getSqlRegisterBounce());
    }

    /**
     * @test
     */
    public function setSqlRegisterBounceForStringSetsSqlRegisterBounce()
    {
        $this->subject->setSqlRegisterBounce('Conceived at T3CON10');
        $this->assertAttributeSame('Conceived at T3CON10', 'sqlRegisterBounce', $this->subject);
    }

    /**
     * @test
     */
    public function getSqlRegisterClickReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getSqlRegisterClick());
    }

    /**
     * @test
     */
    public function setSqlRegisterClickForStringSetsSqlRegisterClick()
    {
        $this->subject->setSqlRegisterClick('Conceived at T3CON10');
        $this->assertAttributeSame('Conceived at T3CON10', 'sqlRegisterClick', $this->subject);
    }
}

<?php

namespace Ecodev\Newsletter\Tests\Unit\Domain\Model\RecipientList;

use Ecodev\Newsletter\Domain\Model\RecipientList\Sql;

/**
 * Test case for class \Ecodev\Newsletter\Domain\Model\Sql.
 */
class SqlTest extends AbstractRecipientList
{
    protected function setUp()
    {
        $this->subject = new Sql();
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

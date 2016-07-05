<?php

namespace Ecodev\Newsletter\Tests\Unit\Domain\Model\RecipientList;

/**
 * Test case for class \Ecodev\Newsletter\Domain\Model\RecipientList\CsvUrl.
 */
class CsvUrlTest extends CsvFileTest
{
    protected function setUp()
    {
        $this->subject = new \Ecodev\Newsletter\Domain\Model\RecipientList\CsvUrl();
    }

    /**
     * @test
     */
    public function getCsvUrlReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getCsvUrl());
    }

    /**
     * @test
     */
    public function setCsvUrlForStringSetsCsvUrl()
    {
        $this->subject->setCsvUrl('Conceived at T3CON10');
        $this->assertAttributeSame('Conceived at T3CON10', 'csvUrl', $this->subject);
    }

    protected function prepareDataForEnumeration()
    {
        $this->subject->setCsvUrl(__DIR__ . '/data.csv');
    }
}

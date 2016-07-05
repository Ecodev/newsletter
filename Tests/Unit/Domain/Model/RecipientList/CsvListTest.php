<?php

namespace Ecodev\Newsletter\Tests\Unit\Domain\Model\RecipientList;

/**
 * Test case for class \Ecodev\Newsletter\Domain\Model\RecipientList\CsvList.
 */
class CsvListTest extends CsvFileTest
{
    protected function setUp()
    {
        $this->subject = new \Ecodev\Newsletter\Domain\Model\RecipientList\CsvList();
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
    public function getCsvValuesReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getCsvValues());
    }

    /**
     * @test
     */
    public function setCsvValuesForStringSetsCsvValues()
    {
        $this->subject->setCsvValues('Conceived at T3CON10');
        $this->assertAttributeSame('Conceived at T3CON10', 'csvValues', $this->subject);
    }

    protected function prepareDataForEnumeration()
    {
        $values = file_get_contents(__DIR__ . '/data.csv');

        $this->subject->setCsvValues($values);
    }
}

<?php

namespace Ecodev\Newsletter\Tests\Unit\Domain\Model\RecipientList;

use Ecodev\Newsletter\Domain\Model\RecipientList\CsvFile;

/**
 * Test case for class \Ecodev\Newsletter\Domain\Model\RecipientList\CsvFile.
 */
class CsvFileTest extends AbstractRecipientList
{
    protected function setUp()
    {
        $this->subject = new CsvFile();
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
        $this->subject = $this->getMock(CsvFile::class, ['getPathname'], [], '', false);
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

        $recipient1 = [
            'email' => 'john@example.com',
            'name' => 'John',
            'some_flags' => '1',
            'plain_only' => false,
        ];

        $recipient2 = [
            'email' => 'bob@example.com',
            'name' => 'Roger',
            'some_flags' => '0',
            'plain_only' => false,
        ];

        $this->assertSame($recipient1, $this->subject->getRecipient());
        $this->assertSame($recipient2, $this->subject->getRecipient());
        $this->assertFalse($this->subject->getRecipient());
    }
}

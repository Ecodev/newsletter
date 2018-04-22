<?php

namespace Ecodev\Newsletter\Tests\Unit\Domain\Model\RecipientList;

/**
 * Test case for class \Ecodev\Newsletter\Domain\Model\CsvList.
 */
abstract class AbstractRecipientList extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var RecipientList
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
        $this->assertAttributeSame('Conceived at T3CON10', 'title', $this->subject);
    }

    /**
     * @test
     */
    public function getPlainOnlyReturnsInitialValueForBoolean()
    {
        $this->assertFalse(
            $this->subject->getPlainOnly()
        );
        $this->assertFalse(
            $this->subject->isPlainOnly()
        );
    }

    /**
     * @test
     */
    public function setPlainOnlyForBooleanSetsPlainOnly()
    {
        $this->subject->setPlainOnly(true);

        $this->assertAttributeSame(
            true, 'plainOnly', $this->subject
        );
    }

    /**
     * @test
     */
    public function getLangReturnsInitialValueForString()
    {
        $this->assertSame(0, $this->subject->getLang());
    }

    /**
     * @test
     */
    public function setLangForStringSetsLang()
    {
        $this->subject->setLang(123);
        $this->assertAttributeSame(123, 'lang', $this->subject);
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
        $this->assertAttributeSame('Conceived at T3CON10', 'type', $this->subject);
    }
}

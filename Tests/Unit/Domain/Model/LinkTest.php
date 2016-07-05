<?php

namespace Ecodev\Newsletter\Tests\Unit\Domain\Model;

/**
 * Test case for class \Ecodev\Newsletter\Domain\Model\Link.
 */
class LinkTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Ecodev\Newsletter\Domain\Model\Link
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = new \Ecodev\Newsletter\Domain\Model\Link();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getUrlReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getUrl());
    }

    /**
     * @test
     */
    public function setUrlForStringSetsUrl()
    {
        $this->subject->setUrl('Conceived at T3CON10');
        $this->assertAttributeSame('Conceived at T3CON10', 'url', $this->subject);
    }

    /**
     * @test
     */
    public function setNewsletterForNewsletterSetsNewsletter()
    {
        $newsletterFixture = new \Ecodev\Newsletter\Domain\Model\Newsletter();
        $this->subject->setNewsletter($newsletterFixture);

        $this->assertAttributeSame(
                $newsletterFixture, 'newsletter', $this->subject
        );
    }

    /**
     * @test
     */
    public function getOpenedCountReturnsInitialValueForInteger()
    {
        $this->assertSame(
                0, $this->subject->getOpenedCount()
        );
    }

    /**
     * @test
     */
    public function setOpenedCountForIntegerSetsOpenedCount()
    {
        $this->subject->setOpenedCount(12);

        $this->assertAttributeSame(
                12, 'openedCount', $this->subject
        );
    }
}

<?php

namespace Ecodev\Newsletter\Tests\Unit\Domain\Model;

use Ecodev\Newsletter\Domain\Model\Email;
use Ecodev\Newsletter\Domain\Model\Newsletter;

/**
 * Test case for class \Ecodev\Newsletter\Domain\Model\Email.
 */
class EmailTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var Email
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = new Email();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getBeginTimeReturnsInitialValueForDateTime()
    {
        $this->assertNull(
            $this->subject->getBeginTime()
        );
    }

    /**
     * @test
     */
    public function setBeginTimeForDateTimeSetsBeginTime()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setBeginTime($dateTimeFixture);

        $this->assertAttributeSame(
            $dateTimeFixture, 'beginTime', $this->subject
        );
    }

    /**
     * @test
     */
    public function getEndTimeReturnsInitialValueForDateTime()
    {
        $this->assertNull(
            $this->subject->getEndTime()
        );
    }

    /**
     * @test
     */
    public function setEndTimeForDateTimeSetsEndTime()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setEndTime($dateTimeFixture);

        $this->assertAttributeSame(
            $dateTimeFixture, 'endTime', $this->subject
        );
    }

    /**
     * @test
     */
    public function getOpenTimeReturnsInitialValueForDateTime()
    {
        $this->assertNull(
            $this->subject->getOpenTime()
        );
    }

    /**
     * @test
     */
    public function setOpenTimeForDateTimeSetsOpenTime()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setOpenTime($dateTimeFixture);

        $this->assertAttributeSame(
            $dateTimeFixture, 'openTime', $this->subject
        );
    }

    /**
     * @test
     */
    public function getBounceTimeReturnsInitialValueForDateTime()
    {
        $this->assertNull(
            $this->subject->getBounceTime()
        );
    }

    /**
     * @test
     */
    public function setBounceTimeForDateTimeSetsBounceTime()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setBounceTime($dateTimeFixture);

        $this->assertAttributeSame(
            $dateTimeFixture, 'bounceTime', $this->subject
        );
    }

    /**
     * @test
     */
    public function getUnsubscribedReturnsInitialValueForBoolean()
    {
        $this->assertFalse(
            $this->subject->getUnsubscribed()
        );
    }

    /**
     * @test
     */
    public function setUnsubscribedForBooleanSetsUnsubscribed()
    {
        $this->subject->setUnsubscribed(true);

        $this->assertAttributeSame(
            true, 'unsubscribed', $this->subject
        );
    }

    /**
     * @test
     */
    public function getRecipientAddressReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getRecipientAddress());
    }

    /**
     * @test
     */
    public function setRecipientAddressForStringSetsRecipientAddress()
    {
        $this->subject->setRecipientAddress('Conceived at T3CON10');
        $this->assertAttributeSame('Conceived at T3CON10', 'recipientAddress', $this->subject);
    }

    /**
     * @test
     */
    public function getRecipientDataReturnsInitialValueForArray()
    {
        $this->assertSame([], $this->subject->getRecipientData());
    }

    /**
     * @test
     */
    public function setRecipientDataForStringSetsRecipientData()
    {
        $this->subject->setRecipientData(['data1', 'data2']);
        $this->assertSame(['data1', 'data2'], $this->subject->getRecipientData());
    }

    /**
     * @test
     */
    public function getNewsletterReturnsInitialValueForNewsletter()
    {
        $this->assertNull(
            $this->subject->getNewsletter()
        );
    }

    /**
     * @test
     */
    public function setNewsletterForNewsletterSetsNewsletter()
    {
        $newsletterFixture = new Newsletter();
        $this->subject->setNewsletter($newsletterFixture);

        $this->assertAttributeSame(
            $newsletterFixture, 'newsletter', $this->subject
        );
    }

    /**
     * @test
     */
    public function isOpened()
    {
        $this->assertFalse($this->subject->isOpened());
        $this->subject->setOpenTime(new \DateTime());
        $this->assertTrue($this->subject->isOpened());
    }

    /**
     * @test
     */
    public function isBounced()
    {
        $this->assertFalse($this->subject->isBounced());
        $this->subject->setBounceTime(new \DateTime());
        $this->assertTrue($this->subject->isBounced());
    }

    /**
     * @test
     */
    public function getAuthCode()
    {
        $email = $this->getMock(Email::class, ['getUid'], [], '', false);
        $email->expects($this->any())->method('getUid')->will($this->returnValue(123));
        $email->setRecipientAddress('john@example.com');
        $this->assertSame('462aa2b1b9885a181e6d916a409d96c8', $email->getAuthCode());
    }
}

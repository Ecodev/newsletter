<?php

namespace Ecodev\Newsletter\Tests\Unit\Domain\Model;

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
 * Test case for class \Ecodev\Newsletter\Domain\Model\Email.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class EmailTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Ecodev\Newsletter\Domain\Model\Email
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = new \Ecodev\Newsletter\Domain\Model\Email();
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
        $this->assertEquals(
                null, $this->subject->getBeginTime()
        );
    }

    /**
     * @test
     */
    public function setBeginTimeForDateTimeSetsBeginTime()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setBeginTime($dateTimeFixture);

        $this->assertAttributeEquals(
                $dateTimeFixture, 'beginTime', $this->subject
        );
    }

    /**
     * @test
     */
    public function getEndTimeReturnsInitialValueForDateTime()
    {
        $this->assertEquals(
                null, $this->subject->getEndTime()
        );
    }

    /**
     * @test
     */
    public function setEndTimeForDateTimeSetsEndTime()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setEndTime($dateTimeFixture);

        $this->assertAttributeEquals(
                $dateTimeFixture, 'endTime', $this->subject
        );
    }

    /**
     * @test
     */
    public function getOpenTimeReturnsInitialValueForDateTime()
    {
        $this->assertEquals(
                null, $this->subject->getOpenTime()
        );
    }

    /**
     * @test
     */
    public function setOpenTimeForDateTimeSetsOpenTime()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setOpenTime($dateTimeFixture);

        $this->assertAttributeEquals(
                $dateTimeFixture, 'openTime', $this->subject
        );
    }

    /**
     * @test
     */
    public function getBounceTimeReturnsInitialValueForDateTime()
    {
        $this->assertEquals(
                null, $this->subject->getBounceTime()
        );
    }

    /**
     * @test
     */
    public function setBounceTimeForDateTimeSetsBounceTime()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setBounceTime($dateTimeFixture);

        $this->assertAttributeEquals(
                $dateTimeFixture, 'bounceTime', $this->subject
        );
    }

    /**
     * @test
     */
    public function getUnsubscribedReturnsInitialValueForBoolean()
    {
        $this->assertSame(
                false, $this->subject->getUnsubscribed()
        );
    }

    /**
     * @test
     */
    public function setUnsubscribedForBooleanSetsUnsubscribed()
    {
        $this->subject->setUnsubscribed(true);

        $this->assertAttributeEquals(
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
        $this->assertAttributeEquals('Conceived at T3CON10', 'recipientAddress', $this->subject);
    }

    /**
     * @test
     */
    public function getRecipientDataReturnsInitialValueForArray()
    {
        $this->assertSame(array(), $this->subject->getRecipientData());
    }

    /**
     * @test
     */
    public function setRecipientDataForStringSetsRecipientData()
    {
        $this->subject->setRecipientData(array('data1', 'data2'));
        $this->assertEquals(array('data1', 'data2'), $this->subject->getRecipientData());
    }

    /**
     * @test
     */
    public function getNewsletterReturnsInitialValueForNewsletter()
    {
        $this->assertEquals(
                null, $this->subject->getNewsletter()
        );
    }

    /**
     * @test
     */
    public function setNewsletterForNewsletterSetsNewsletter()
    {
        $newsletterFixture = new \Ecodev\Newsletter\Domain\Model\Newsletter();
        $this->subject->setNewsletter($newsletterFixture);

        $this->assertAttributeEquals(
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
        $email = $this->getMock('Ecodev\\Newsletter\\Domain\\Model\\Email', array('getUid'), array(), '', false);
        $email->expects($this->once())->method('getUid')->will($this->returnValue(123));
        $email->setRecipientAddress('john@example.com');
        $this->assertEquals('462aa2b1b9885a181e6d916a409d96c8', $email->getAuthCode());
    }
}

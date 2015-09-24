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
 * Test case for class \Ecodev\Newsletter\Domain\Model\Newsletter.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class NewsletterTest extends \Ecodev\Newsletter\Tests\Unit\AbstractUnitTestCase
{
    /**
     * @var \Ecodev\Newsletter\Domain\Model\Newsletter
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->loadConfiguration();
        $this->subject = new \Ecodev\Newsletter\Domain\Model\Newsletter();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function setUid()
    {
        $this->assertNull($this->subject->getUid());
        $this->subject->setUid(123);
        $this->assertEquals(123, $this->subject->getUid());
    }

    /**
     * @test
     */
    public function getPlannedTimeReturnsInitialValueForDateTime()
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        $plannedTime = $this->subject->getPlannedTime();
        $this->assertNotNull($plannedTime);

        $plannedTime->setTime(0, 0, 0);
        $this->assertEquals($today, $plannedTime);
    }

    /**
     * @test
     */
    public function setPlannedTimeForDateTimeSetsPlannedTime()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setPlannedTime($dateTimeFixture);

        $this->assertAttributeEquals(
                $dateTimeFixture, 'plannedTime', $this->subject
        );
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
    public function getRepetitionReturnsInitialValueForInteger()
    {
        $this->assertSame(
                0, $this->subject->getRepetition()
        );
    }

    /**
     * @test
     */
    public function setRepetitionForIntegerSetsRepetition()
    {
        $this->subject->setRepetition(12);

        $this->assertAttributeEquals(
                12, 'repetition', $this->subject
        );
    }

    /**
     * @test
     */
    public function getPlainConverterReturnsInitialValueForString()
    {
        $converter = $this->subject->getPlainConverter();
        $this->assertSame(
                'Ecodev\\Newsletter\\Domain\\Model\\PlainConverter\\Builtin', $converter
        );

        $this->assertTrue(class_exists($converter));
    }

    /**
     * @test
     */
    public function setPlainConverterForStringSetsPlainConverter()
    {
        $this->subject->setPlainConverter('Conceived at T3CON10');

        $this->assertAttributeEquals(
                'Conceived at T3CON10', 'plainConverter', $this->subject
        );
    }

    /**
     * @test
     */
    public function getPlainConverterInstance()
    {
        $classes = array(
            'NonExistingClassFooBar' => 'Ecodev\\Newsletter\\Domain\\Model\\PlainConverter\\Builtin',
            'Ecodev\\Newsletter\\Domain\\Model\\PlainConverter\\Builtin' => 'Ecodev\\Newsletter\\Domain\\Model\\PlainConverter\\Builtin',
            'Ecodev\\Newsletter\\Domain\\Model\\PlainConverter\\Lynx' => 'Ecodev\\Newsletter\\Domain\\Model\\PlainConverter\\Lynx',
        );

        foreach ($classes as $class => $expected) {
            $this->subject->setPlainConverter($class);
            $this->assertInstanceOf($expected, $this->subject->getPlainConverterInstance());
        }
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function getPlainConverterInstanceThrowsException()
    {
        $this->subject->setPlainConverter('stdClass');
        $this->subject->getPlainConverterInstance();
    }

    /**
     * @test
     */
    public function getIsTestReturnsInitialValueForBoolean()
    {
        $this->assertSame(false, $this->subject->getIsTest());
        $this->assertSame(false, $this->subject->isIsTest());
    }

    /**
     * @test
     */
    public function setIsTestForBooleanSetsIsTest()
    {
        $this->subject->setIsTest(true);

        $this->assertAttributeEquals(
                true, 'isTest', $this->subject
        );
    }

    /**
     * @test
     */
    public function getBounceAccountReturnsInitialValueForBounceAccount()
    {
        $this->assertEquals(
                null, $this->subject->getBounceAccount()
        );
    }

    /**
     * @test
     */
    public function setBounceAccountForBounceAccountSetsBounceAccount()
    {
        $bounceAccountFixture = new \Ecodev\Newsletter\Domain\Model\BounceAccount();
        $this->subject->setBounceAccount($bounceAccountFixture);

        $this->assertAttributeEquals(
                $bounceAccountFixture, 'bounceAccount', $this->subject
        );
    }

    /**
     * @test
     */
    public function getUidBounceAccount()
    {
        $this->assertNull($this->subject->getUidBounceAccount());

        $bounceAccount = $this->getMock('Ecodev\\Newsletter\\Domain\\Model\\BounceAccount', array('getUid'), array(), '', false);
        $bounceAccount->expects($this->once())->method('getUid')->will($this->returnValue(123));
        $this->subject->setBounceAccount($bounceAccount);
        $this->assertEquals(123, $this->subject->getUidBounceAccount());
    }

    /**
     * @test
     */
    public function setSenderNameForStringSetsSenderName()
    {
        $this->subject->setSenderName('Conceived at T3CON10');

        $this->assertAttributeEquals(
                'Conceived at T3CON10', 'senderName', $this->subject
        );

        $this->assertEquals('Conceived at T3CON10', $this->subject->getSenderName());
    }

    /**
     * @test
     */
    public function setSenderEmailForStringSetsSenderEmail()
    {
        $this->subject->setSenderEmail('john@example.com');

        $this->assertAttributeEquals(
                'john@example.com', 'senderEmail', $this->subject
        );

        $this->assertEquals('john@example.com', $this->subject->getSenderEmail());
    }

    /**
     * @test
     */
    public function getInjectOpenSpyReturnsInitialValueForBoolean()
    {
        $this->assertSame(true, $this->subject->getInjectOpenSpy());
        $this->assertSame(true, $this->subject->isInjectOpenSpy());
    }

    /**
     * @test
     */
    public function setInjectOpenSpyForBooleanSetssetInjectOpenSpy()
    {
        $this->subject->setInjectOpenSpy(true);

        $this->assertAttributeEquals(
                true, 'injectOpenSpy', $this->subject
        );
    }

    /**
     * @test
     */
    public function getInjectLinksSpyReturnsInitialValueForBoolean()
    {
        $this->assertSame(true, $this->subject->getInjectLinksSpy());
        $this->assertSame(true, $this->subject->isInjectLinksSpy());
    }

    /**
     * @test
     */
    public function setInjectLinksSpyForBooleanSetsInjectLinksSpy()
    {
        $this->subject->setInjectLinksSpy(false);

        $this->assertAttributeEquals(
                false, 'injectLinksSpy', $this->subject
        );
    }

    /**
     * @test
     */
    public function getRecipientListReturnsInitialValueForRecipientList()
    {
        $this->assertEquals(
                null, $this->subject->getRecipientList()
        );
    }

    /**
     * @test
     */
    public function setRecipientListForRecipientListSetsRecipientList()
    {
        $recipientListFixture = new \Ecodev\Newsletter\Domain\Model\RecipientList\BeUsers();
        $this->subject->setRecipientList($recipientListFixture);

        $this->assertAttributeEquals(
                $recipientListFixture, 'recipientList', $this->subject
        );
    }

    /**
     * @test
     */
    public function getUidRecipientList()
    {
        $this->assertNull($this->subject->getUidRecipientList());

        $recipientList = $this->getMock('Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\BeUsers', array('getUid'), array(), '', false);
        $recipientList->expects($this->once())->method('getUid')->will($this->returnValue(123));
        $this->subject->setRecipientList($recipientList);
        $this->assertEquals(123, $this->subject->getUidRecipientList());
    }

    /**
     * @test
     */
    public function getReplytoName()
    {
        $this->assertSame('John Connor', $this->subject->getReplytoName(), 'sould return globally configured default value');
        $this->subject->setReplytoName('My custom name');
        $this->assertSame('My custom name', $this->subject->getReplytoName(), 'sould return locally set value');
    }

    /**
     * @test
     */
    public function getReplytoEmail()
    {
        $this->assertSame('john.connor@example.com', $this->subject->getReplytoEmail(), 'sould return globally configured default value');
        $this->subject->setReplytoEmail('custom@example.com');
        $this->assertSame('custom@example.com', $this->subject->getReplytoEmail(), 'sould return locally set value');
    }
}

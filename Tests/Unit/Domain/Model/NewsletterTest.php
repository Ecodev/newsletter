<?php

namespace Ecodev\Newsletter\Tests\Unit\Domain\Model;

/**
 * Test case for class \Ecodev\Newsletter\Domain\Model\Newsletter.
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
        $this->assertSame(123, $this->subject->getUid());
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
        $this->assertSame($today->format(\DateTime::ISO8601), $plannedTime->format(\DateTime::ISO8601));
    }

    /**
     * @test
     */
    public function setPlannedTimeForDateTimeSetsPlannedTime()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setPlannedTime($dateTimeFixture);

        $this->assertAttributeSame(
                $dateTimeFixture, 'plannedTime', $this->subject
        );
    }

    /**
     * @test
     */
    public function getBeginTimeReturnsInitialValueForDateTime()
    {
        $this->assertSame(
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

        $this->assertAttributeSame(
                $dateTimeFixture, 'beginTime', $this->subject
        );
    }

    /**
     * @test
     */
    public function getEndTimeReturnsInitialValueForDateTime()
    {
        $this->assertSame(
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

        $this->assertAttributeSame(
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

        $this->assertAttributeSame(
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
                \Ecodev\Newsletter\Domain\Model\PlainConverter\Builtin::class, $converter
        );

        $this->assertTrue(class_exists($converter));
    }

    /**
     * @test
     */
    public function setPlainConverterForStringSetsPlainConverter()
    {
        $this->subject->setPlainConverter('Conceived at T3CON10');

        $this->assertAttributeSame(
                'Conceived at T3CON10', 'plainConverter', $this->subject
        );
    }

    /**
     * @test
     */
    public function getPlainConverterInstance()
    {
        $classes = [
            'NonExistingClassFooBar' => \Ecodev\Newsletter\Domain\Model\PlainConverter\Builtin::class,
            \Ecodev\Newsletter\Domain\Model\PlainConverter\Builtin::class => \Ecodev\Newsletter\Domain\Model\PlainConverter\Builtin::class,
            \Ecodev\Newsletter\Domain\Model\PlainConverter\Lynx::class => \Ecodev\Newsletter\Domain\Model\PlainConverter\Lynx::class,
        ];

        foreach ($classes as $class => $expected) {
            $this->subject->setPlainConverter($class);
            $this->assertInstanceOf($expected, $this->subject->getPlainConverterInstance());
        }
    }

    /**
     * @test
     * @expectedException \Exception
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

        $this->assertAttributeSame(
                true, 'isTest', $this->subject
        );
    }

    /**
     * @test
     */
    public function getBounceAccountReturnsInitialValueForBounceAccount()
    {
        $this->assertSame(
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

        $this->assertAttributeSame(
                $bounceAccountFixture, 'bounceAccount', $this->subject
        );
    }

    /**
     * @test
     */
    public function getUidBounceAccount()
    {
        $this->assertNull($this->subject->getUidBounceAccount());

        $bounceAccount = $this->getMock(\Ecodev\Newsletter\Domain\Model\BounceAccount::class, ['getUid'], [], '', false);
        $bounceAccount->expects($this->once())->method('getUid')->will($this->returnValue(123));
        $this->subject->setBounceAccount($bounceAccount);
        $this->assertSame(123, $this->subject->getUidBounceAccount());
    }

    /**
     * @test
     */
    public function setSenderNameForStringSetsSenderName()
    {
        $this->subject->setSenderName('Conceived at T3CON10');

        $this->assertAttributeSame(
                'Conceived at T3CON10', 'senderName', $this->subject
        );

        $this->assertSame('Conceived at T3CON10', $this->subject->getSenderName());
    }

    /**
     * @test
     */
    public function setSenderEmailForStringSetsSenderEmail()
    {
        $this->subject->setSenderEmail('john@example.com');

        $this->assertAttributeSame(
                'john@example.com', 'senderEmail', $this->subject
        );

        $this->assertSame('john@example.com', $this->subject->getSenderEmail());
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

        $this->assertAttributeSame(
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

        $this->assertAttributeSame(
                false, 'injectLinksSpy', $this->subject
        );
    }

    /**
     * @test
     */
    public function getRecipientListReturnsInitialValueForRecipientList()
    {
        $this->assertSame(
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

        $this->assertAttributeSame(
                $recipientListFixture, 'recipientList', $this->subject
        );
    }

    /**
     * @test
     */
    public function getUidRecipientList()
    {
        $this->assertNull($this->subject->getUidRecipientList());

        $recipientList = $this->getMock(\Ecodev\Newsletter\Domain\Model\RecipientList\BeUsers::class, ['getUid'], [], '', false);
        $recipientList->expects($this->once())->method('getUid')->will($this->returnValue(123));
        $this->subject->setRecipientList($recipientList);
        $this->assertSame(123, $this->subject->getUidRecipientList());
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

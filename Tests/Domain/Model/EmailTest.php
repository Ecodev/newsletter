<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014
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
 * Testcase for class Tx_Newsletter_Domain_Model_Email.
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Newsletter_Domain_Model_EmailTest extends Tx_Extbase_Tests_Unit_BaseTestCase
{

    /**
     * @var Tx_Newsletter_Domain_Model_Email
     */
    protected $fixture;

    public function setUp()
    {
        $this->fixture = new Tx_Newsletter_Domain_Model_Email();
    }

    public function tearDown()
    {
        unset($this->fixture);
    }

    /**
     * @test
     */
    public function getStartTimeReturnsInitialValueForString()
    {

    }

    /**
     * @test
     */
    public function setStartTimeForStringSetsStartTime()
    {
        $this->fixture->setStartTime('Conceived at T3CON10');

        $this->assertSame(
                'Conceived at T3CON10', $this->fixture->getStartTime()
        );
    }

    /**
     * @test
     */
    public function getEndTimeReturnsInitialValueForString()
    {

    }

    /**
     * @test
     */
    public function setEndTimeForStringSetsEndTime()
    {
        $this->fixture->setEndTime('Conceived at T3CON10');

        $this->assertSame(
                'Conceived at T3CON10', $this->fixture->getEndTime()
        );
    }

    /**
     * @test
     */
    public function getRecipientAddressReturnsInitialValueForString()
    {

    }

    /**
     * @test
     */
    public function setRecipientAddressForStringSetsRecipientAddress()
    {
        $this->fixture->setRecipientAddress('Conceived at T3CON10');

        $this->assertSame(
                'Conceived at T3CON10', $this->fixture->getRecipientAddress()
        );
    }

    /**
     * @test
     */
    public function getRecipientDataReturnsInitialValueForString()
    {

    }

    /**
     * @test
     */
    public function setRecipientDataForStringSetsRecipientData()
    {
        $this->fixture->setRecipientData('Conceived at T3CON10');

        $this->assertSame(
                'Conceived at T3CON10', $this->fixture->getRecipientData()
        );
    }

    /**
     * @test
     */
    public function getAuthCodeReturnsInitialValueForString()
    {

    }

    /**
     * @test
     */
    public function getOpenedReturnsInitialValueForBoolean()
    {
        $this->assertSame(
                TRUE, $this->fixture->getOpened()
        );
    }

    /**
     * @test
     */
    public function setOpenedForBooleanSetsOpened()
    {
        $this->fixture->setOpened(TRUE);

        $this->assertSame(
                TRUE, $this->fixture->getOpened()
        );
    }

    /**
     * @test
     */
    public function getBouncedReturnsInitialValueForBoolean()
    {
        $this->assertSame(
                TRUE, $this->fixture->getBounced()
        );
    }

    /**
     * @test
     */
    public function setBouncedForBooleanSetsBounced()
    {
        $this->fixture->setBounced(TRUE);

        $this->assertSame(
                TRUE, $this->fixture->getBounced()
        );
    }

    /**
     * @test
     */
    public function getNewsletterReturnsInitialValueForTx_Newsletter_Domain_Model_Newsletter()
    {
        $this->assertEquals(
                NULL, $this->fixture->getNewsletter()
        );
    }

    /**
     * @test
     */
    public function setNewsletterForTx_Newsletter_Domain_Model_NewsletterSetsNewsletter()
    {
        $dummyObject = new Tx_Newsletter_Domain_Model_Newsletter();
        $this->fixture->setNewsletter($dummyObject);

        $this->assertSame(
                $dummyObject, $this->fixture->getNewsletter()
        );
    }

}

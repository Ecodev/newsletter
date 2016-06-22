<?php

namespace Ecodev\Newsletter\Tests\Functional\Repository;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
require_once __DIR__ . '/../AbstractFunctionalTestCase.php';

/**
 * Functional test for the \Ecodev\Newsletter\Domain\Repository\NewsletterRepository
 */
class NewsletterRepositoryTest extends \Ecodev\Newsletter\Tests\Functional\AbstractFunctionalTestCase
{
    /** @var \Ecodev\Newsletter\Domain\Repository\NewsletterRepository */
    private $newsletterRepository;

    public function setUp()
    {
        parent::setUp();
        $this->newsletterRepository = $this->objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\NewsletterRepository');
    }

    public function testGetLatest()
    {
        $newsletter1 = $this->newsletterRepository->getLatest(1);
        $this->assertNull($newsletter1, 'should not find any newsletter on PID 1');

        $newsletter2 = $this->newsletterRepository->getLatest(2);
        $this->assertNotNull($newsletter2, 'should find newsletter...');

        $this->assertSame(20, $newsletter2->getUid(), '...with UID 20, not UID 30');
    }

    public function testFindAllByPid()
    {
        $newsletters1 = $this->newsletterRepository->findAllByPid(0);
        $this->assertCount(3, $newsletters1, 'should find from all PID');

        $newsletters2 = $this->newsletterRepository->findAllByPid(2);
        $this->assertCount(2, $newsletters2, 'should find only from PID 2');
    }

    public function testFindAllReadyToSend()
    {
        $newsletters1 = $this->newsletterRepository->findAllReadyToSend();
        $this->assertCount(1, $newsletters1, 'should find only one');
        $this->assertSame(20, $newsletters1[0]->getUid());
    }

    public function testFindAllBeingSent()
    {
        $newsletters1 = $this->newsletterRepository->findAllBeingSent();
        $this->assertCount(1, $newsletters1, 'should find only one');
        $this->assertSame(30, $newsletters1[0]->getUid());
    }

    public function testGetStatistics()
    {
        $newsletter = $this->newsletterRepository->findByUid(30);
        $stats = $this->newsletterRepository->getStatistics($newsletter);

        $expected = [
            [
                'time' => 1423729050,
                'emailNotSentCount' => 2,
                'emailSentCount' => 0,
                'emailOpenedCount' => 0,
                'emailBouncedCount' => 0,
                'emailCount' => 2,
                'linkOpenedCount' => 0,
                'linkCount' => 2,
                'emailNotSentPercentage' => 100,
                'emailSentPercentage' => 0,
                'emailOpenedPercentage' => 0,
                'emailBouncedPercentage' => 0,
                'linkOpenedPercentage' => 0,
            ],
            [
                'time' => 1423729053,
                'emailNotSentCount' => 1,
                'emailSentCount' => 1,
                'emailOpenedCount' => 0,
                'emailBouncedCount' => 0,
                'emailCount' => 2,
                'linkOpenedCount' => 0,
                'linkCount' => 2,
                'emailNotSentPercentage' => 50.0,
                'emailSentPercentage' => 50.0,
                'emailOpenedPercentage' => 0,
                'emailBouncedPercentage' => 0,
                'linkOpenedPercentage' => 0,
            ],
            [
                'time' => 1423729055,
                'emailNotSentCount' => 1,
                'emailSentCount' => 0,
                'emailOpenedCount' => 1,
                'emailBouncedCount' => 0,
                'emailCount' => 2,
                'linkOpenedCount' => 0,
                'linkCount' => 2,
                'emailNotSentPercentage' => 50.0,
                'emailSentPercentage' => 0,
                'emailOpenedPercentage' => 50.0,
                'emailBouncedPercentage' => 0,
                'linkOpenedPercentage' => 0,
            ],
            [
                'time' => 1423729056,
                'emailNotSentCount' => 1,
                'emailSentCount' => 0,
                'emailOpenedCount' => 1,
                'emailBouncedCount' => 0,
                'emailCount' => 2,
                'linkOpenedCount' => 1,
                'linkCount' => 2,
                'emailNotSentPercentage' => 50.0,
                'emailSentPercentage' => 0,
                'emailOpenedPercentage' => 50.0,
                'emailBouncedPercentage' => 0,
                'linkOpenedPercentage' => 25.0,
            ],
        ];

        $this->assertSame($expected, $stats);
    }
}

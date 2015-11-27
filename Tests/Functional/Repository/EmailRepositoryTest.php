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
 * Functional test for the \Ecodev\Newsletter\Domain\Repository\EmailRepository
 */
class EmailRepositoryTest extends \Ecodev\Newsletter\Tests\Functional\AbstractFunctionalTestCase
{
    /** @var \Ecodev\Newsletter\Domain\Repository\EmailRepository */
    private $emailRepository;

    public function setUp()
    {
        parent::setUp();
        $this->emailRepository = $this->objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\EmailRepository');
    }

    public function testFindByAuthcode()
    {
        $email = $this->emailRepository->findByAuthcode($this->authCode);
        $this->assertNotNull($email);
        $this->assertSame(302, $email->getUid());
    }

    public function testGetCount()
    {
        $this->assertSame(0, $this->emailRepository->getCount(10));
        $this->assertSame(2, $this->emailRepository->getCount(30));
    }

    public function testFindAllByNewsletter()
    {
        $this->assertCount(0, $this->emailRepository->findAllByNewsletter(10, 0, 999));

        $emails = $this->emailRepository->findAllByNewsletter(30, 0, 999);
        $this->assertCount(2, $emails);
        $this->assertSame(301, $emails[0]->getUid());
        $this->assertSame(302, $emails[1]->getUid());

        $emails = $this->emailRepository->findAllByNewsletter(30, 1, 999);
        $this->assertCount(1, $emails);
        $this->assertSame(302, $emails[0]->getUid());

        $emails = $this->emailRepository->findAllByNewsletter(30, 2, 999);
        $this->assertCount(0, $emails);

        $emails = $this->emailRepository->findAllByNewsletter(30, 0, 1);
        $this->assertCount(1, $emails);
        $this->assertSame(301, $emails[0]->getUid());

        $emails = $this->emailRepository->findAllByNewsletter(30, 1, 1);
        $this->assertCount(1, $emails);
        $this->assertSame(302, $emails[0]->getUid());

        $emails = $this->emailRepository->findAllByNewsletter(30, 2, 1);
        $this->assertCount(0, $emails);
    }

    public function testRegisterOpen()
    {
        $this->emailRepository->registerOpen($this->authCode);

        $email = $this->emailRepository->findByUid(302);
        $this->assertTrue($email->isOpened(), 'email should be marked as opened');
        $this->assertRecipientListCallbackWasCalled('opened recipient2@example.com');
    }
}

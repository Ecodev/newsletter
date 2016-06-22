<?php

namespace Ecodev\Newsletter\Tests\Functional;

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
require_once __DIR__ . '/AbstractFunctionalTestCase.php';

/**
 * Functional test for the \Ecodev\Newsletter\BounceHandler
 */
class BounceHandlerTest extends \Ecodev\Newsletter\Tests\Functional\AbstractFunctionalTestCase
{
    public function testDispatch()
    {
        $content = <<<STRING
Delivered-To: bounce@example.com
Received: by 10.10.10.10 with SMTP id a59csp784285qge;
        Thu, 8 Jan 2015 00:07:47 -0800 (PST)
From: MAILER-DAEMON@example.com (Mail Delivery System)
Subject: Undelivered Mail Returned to Sender
To: recipient2@example.com
Message-ID: <$this->authCode@example.com>

This is the mail system at host mail.example.com.

I'm sorry to have to inform you that your message could not
be delivered to one or more recipients. It's attached below.

For further assistance, please send mail to postmaster.

If you do so, please include this problem report. You can
delete your own text from the attached returned message.

                   The mail system

STRING;

        $bounceHandler = new \Ecodev\Newsletter\BounceHandler($content);
        $bounceHandler->dispatch();

        $emailRepository = $this->objectManager->get(\Ecodev\Newsletter\Domain\Repository\EmailRepository::class);
        $email = $emailRepository->findByUid(302);
        $this->assertTrue($email->isBounced());
        $this->assertRecipientListCallbackWasCalled('bounced recipient2@example.com, 2, 2, 3, 4');
    }
}

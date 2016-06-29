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
        $filename = dirname(__DIR__) . '/Unit/Fixtures/bounce/2-87c4e9b09085befbb7f20faa7482213a-Undelivered Mail Returned to Sender.eml';
        $content = file_get_contents($filename);

        $bounceHandler = new \Ecodev\Newsletter\BounceHandler($content);
        $bounceHandler->dispatch();

        $emailRepository = $this->objectManager->get(\Ecodev\Newsletter\Domain\Repository\EmailRepository::class);
        $email = $emailRepository->findByUid(302);
        $this->assertTrue($email->isBounced());
        $this->assertRecipientListCallbackWasCalled('bounced recipient2@example.com, 2, 2, 3, 4');
    }
}

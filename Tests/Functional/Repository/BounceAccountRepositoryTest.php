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
 * Functional test for the \Ecodev\Newsletter\Domain\Repository\BounceAccountRepository
 */
class BounceAccountRepositoryTest extends \Ecodev\Newsletter\Tests\Functional\AbstractFunctionalTestCase
{
    /** @var \Ecodev\Newsletter\Domain\Repository\BounceAccountRepository */
    private $bounceAccountRepository;

    public function setUp()
    {
        parent::setUp();
        $this->bounceAccountRepository = $this->objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\BounceAccountRepository');
    }

    public function testFindFirst()
    {
        $bounceAccount = $this->bounceAccountRepository->findFirst();
        $this->assertNotNull($bounceAccount);
        $this->assertEquals(666, $bounceAccount->getUid());
    }
}

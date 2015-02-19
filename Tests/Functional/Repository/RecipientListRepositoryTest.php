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
 * Functional test for the \Ecodev\Newsletter\Domain\Repository\RecipientListRepository
 */
class RecipientListRepositoryTest extends \Ecodev\Newsletter\Tests\Functional\AbstractFunctionalTestCase
{
    /** @var \Ecodev\Newsletter\Domain\Repository\RecipientListRepository */
    private $recipientListRepository;

    public function setUp()
    {
        parent::setUp();
        $this->recipientListRepository = $this->objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\RecipientListRepository');
    }

    public function testFindByUidInitialized()
    {
        $recipientList = $this->recipientListRepository->findByUidInitialized(1000);
        $this->assertNotNull($recipientList);
        $this->assertEquals(2, $recipientList->getCount(), 'should not have to call init() to get count');
    }
}

<?php

namespace Ecodev\Newsletter\Tests\Functional\Repository;

use Ecodev\Newsletter\Domain\Repository\RecipientListRepository;

require_once __DIR__ . '/../AbstractFunctionalTestCase.php';

/**
 * Functional test for the \Ecodev\Newsletter\Domain\Repository\RecipientListRepository
 */
class RecipientListRepositoryTest extends \Ecodev\Newsletter\Tests\Functional\AbstractFunctionalTestCase
{
    /** @var RecipientListRepository */
    private $recipientListRepository;

    public function setUp()
    {
        parent::setUp();
        $this->recipientListRepository = $this->objectManager->get(RecipientListRepository::class);
    }

    public function testFindByUidInitialized()
    {
        $recipientList = $this->recipientListRepository->findByUidInitialized(1000);
        $this->assertNotNull($recipientList);
        $this->assertSame(2, $recipientList->getCount(), 'should not have to call init() to get count');
    }
}

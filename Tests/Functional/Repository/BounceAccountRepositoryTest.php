<?php

namespace Ecodev\Newsletter\Tests\Functional\Repository;

use Ecodev\Newsletter\Domain\Repository\BounceAccountRepository;

require_once __DIR__ . '/../AbstractFunctionalTestCase.php';

/**
 * Functional test for the \Ecodev\Newsletter\Domain\Repository\BounceAccountRepository
 */
class BounceAccountRepositoryTest extends \Ecodev\Newsletter\Tests\Functional\AbstractFunctionalTestCase
{
    /** @var BounceAccountRepository */
    private $bounceAccountRepository;

    public function setUp()
    {
        parent::setUp();
        $this->bounceAccountRepository = $this->objectManager->get(BounceAccountRepository::class);
    }

    public function testFindFirst()
    {
        $bounceAccount = $this->bounceAccountRepository->findFirst();
        $this->assertNotNull($bounceAccount);
        $this->assertSame(666, $bounceAccount->getUid());
    }
}

<?php

namespace Ecodev\Newsletter\Tests\Functional\Repository;

use Ecodev\Newsletter\Domain\Repository\EmailRepository;
use Ecodev\Newsletter\Domain\Repository\LinkRepository;

require_once __DIR__ . '/../AbstractFunctionalTestCase.php';

/**
 * Functional test for the \Ecodev\Newsletter\Domain\Repository\LinkRepository
 */
class LinkRepositoryTest extends \Ecodev\Newsletter\Tests\Functional\AbstractFunctionalTestCase
{
    /** @var LinkRepository */
    private $linkRepository;

    /** @var EmailRepository */
    private $emailRepository;

    public function setUp()
    {
        parent::setUp();
        $this->linkRepository = $this->objectManager->get(LinkRepository::class);
        $this->emailRepository = $this->objectManager->get(EmailRepository::class);

        // When testing we need to help the core by filling HTTP_HOST variable to be able to build correct URL
        $_SERVER['HTTP_HOST'] = 'example.com';
        $_SERVER['SCRIPT_NAME'] = '/index.php';
    }

    public function testFindAllByNewsletter()
    {
        $this->assertCount(0, $this->linkRepository->findAllByNewsletter(10, 0, 999));

        $links = $this->linkRepository->findAllByNewsletter(30, 0, 999);
        $this->assertCount(2, $links);
        $this->assertSame(3001, $links[0]->getUid());
        $this->assertSame(3002, $links[1]->getUid());

        $links = $this->linkRepository->findAllByNewsletter(30, 1, 999);
        $this->assertCount(1, $links);
        $this->assertSame(3002, $links[0]->getUid());

        $links = $this->linkRepository->findAllByNewsletter(30, 2, 999);
        $this->assertCount(0, $links);

        $links = $this->linkRepository->findAllByNewsletter(30, 0, 1);
        $this->assertCount(1, $links);
        $this->assertSame(3001, $links[0]->getUid());

        $links = $this->linkRepository->findAllByNewsletter(30, 1, 1);
        $this->assertCount(1, $links);
        $this->assertSame(3002, $links[0]->getUid());

        $links = $this->linkRepository->findAllByNewsletter(30, 2, 1);
        $this->assertCount(0, $links);
    }

    public function testGetCount()
    {
        $this->assertSame(0, $this->linkRepository->getCount(10));
        $this->assertSame(2, $this->linkRepository->getCount(30));
    }

    public function testRegisterClick()
    {
        $authCodeForLink = md5($this->authCode . 3001);
        $url = $this->linkRepository->registerClick(30, $authCodeForLink, false);
        $this->assertSame('http://example.com/index.php?id=3&type=1342671779&tx_newsletter_p%5Baction%5D=show&tx_newsletter_p%5Bcontroller%5D=Email&tx_newsletter_p%5Bc%5D=87c4e9b09085befbb7f20faa7482213a', $url, 'the URL returned must have markers substituted');

        $link = $this->linkRepository->findByUid(3001);
        $this->assertSame(1, $link->getOpenedCount(), 'the link opened count must have been incrementated');
        $this->assertRecipientListCallbackWasCalled('clicked recipient2@example.com');

        $db = $this->getDatabaseConnection();
        $count = $db->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_linkopened', 'link = 3001 AND email = 302');
        $this->assertSame(1, $count, 'must have exactly 1 linkopened record for this link');

        $email = $this->emailRepository->findByUid(302);
        $this->assertTrue($email->isOpened(), 'email should be marked as open, even if the open spy did not work, because a link was clicked');
        $this->assertRecipientListCallbackWasCalled('opened recipient2@example.com');
    }
}

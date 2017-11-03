<?php

namespace Ecodev\Newsletter\Tests\Functional;

use Ecodev\Newsletter\Domain\Model\BounceAccount;
use Ecodev\Newsletter\Domain\Model\Email;
use Ecodev\Newsletter\Domain\Model\Newsletter;
use Ecodev\Newsletter\Mailer;

/**
 * Test case for class Ecodev\Newsletter\Mailer.
 */
class MailerTest extends \Ecodev\Newsletter\Tests\Functional\AbstractFunctionalTestCase
{
    /**
     * @var Newsletter
     */
    private $mockNewsletter = null;

    /**
     * @var Email
     */
    private $mockEmail = null;

    public function setUp()
    {
        parent::setUp();
        $bounceAccount = new BounceAccount();
        $bounceAccount->setEmail('bounce@example.com');

        $this->mockNewsletter = $this->getMock(Newsletter::class, ['getUid', 'getPid', 'getBaseUrl', 'getSenderName', 'getSenderEmail', 'getBounceAccount', 'getValidatedContent', 'getInjectOpenSpy', 'getInjectLinksSpy'], [], '', false);
        $this->mockNewsletter->method('getUid')->will($this->returnValue(12345));
        $this->mockNewsletter->method('getBaseUrl')->will($this->returnValue('http://example.com'));
        $this->mockNewsletter->method('getSenderName')->will($this->returnValue('John Connor'));
        $this->mockNewsletter->method('getSenderEmail')->will($this->returnValue('noreply@example.com'));
        $this->mockNewsletter->method('getBounceAccount')->will($this->returnValue($bounceAccount));

        $this->mockEmail = $this->getMock(Email::class, ['getPid', 'getRecipientAddress', 'getAuthCode'], [], '', false);
        $this->mockEmail->method('getRecipientAddress')->will($this->returnValue('recipient@example.com'));
        $this->mockEmail->method('getAuthCode')->will($this->returnValue('1621db76eb1e4352719c95f3ba617990'));
        $this->mockEmail->setRecipientData([
            'email' => 'recipient@example.com',
            'my_custom_field' => 'my custom value',
            'boolean_false' => false,
            'boolean_true' => true,
            'integer_false' => 0,
            'integer_true' => 1,
            'string_false' => '',
            'string_true' => 'foo',
        ]);

        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['newsletter'] = serialize(['attach_images' => true]);
    }

    private function getData($pid, $injectOpenSpy, $injectLinksSpy)
    {
        $folder = __DIR__ . '/Fixtures/mailer';
        $flags = implode('-', [$pid, var_export($injectOpenSpy, true), var_export($injectLinksSpy, true)]);

        return [
            $pid,
            $injectOpenSpy,
            $injectLinksSpy,
            $folder . '/input.html',
            $folder . "/output-$flags.eml",
        ];
    }

    public function dataProviderTestMailer()
    {
        $data = [];
        foreach ([false, true] as $injectLinksSpy) {
            foreach ([false, true] as $injectOpenSpy) {
                $data[] = $this->getData(2, $injectOpenSpy, $injectLinksSpy);
            }
        }

        // One more test with a different PID that should output different domains
        $data[] = $this->getData(6, true, true);

        return $data;
    }

    /**
     * @dataProvider dataProviderTestMailer
     * @param int $pid
     * @param bool $injectOpenSpy
     * @param bool $injectLinksSpy
     * @param string $inputFile
     * @param string $expectedEmailFile
     */
    public function testMailer($pid, $injectOpenSpy, $injectLinksSpy, $inputFile, $expectedEmailFile)
    {
        $input = file_get_contents($inputFile);
        $expectedEmail = file_get_contents($expectedEmailFile);

        $this->mockNewsletter->method('getValidatedContent')->will($this->returnValue(
                [
                    'content' => $input,
                    'errors' => [],
                    'warnings' => [],
                    'infos' => [],
                ]
        ));
        $this->mockNewsletter->method('getInjectOpenSpy')->will($this->returnValue($injectOpenSpy));
        $this->mockNewsletter->method('getInjectLinksSpy')->will($this->returnValue($injectLinksSpy));
        $this->mockNewsletter->method('getPid')->will($this->returnValue($pid));
        $this->mockEmail->method('getPid')->will($this->returnValue($pid));

        $mailer = $this->objectManager->get(Mailer::class);

        $mailer->setNewsletter($this->mockNewsletter);
        $mailer->prepare($this->mockEmail);
        $message = $mailer->createMessage($this->mockEmail);
        $actualEmail = $message->toString();

        $logFile = '/tmp/' . basename($expectedEmailFile);
        file_put_contents($logFile, $actualEmail);

        $this->assertSame($this->unrandomizeEmail($expectedEmail), $this->unrandomizeEmail($actualEmail));

        if ($injectLinksSpy) {
            $this->assertLinkWasCreated('http://www.example.com');
            $this->assertLinkWasCreated('http://###my_custom_field###');
            $this->assertLinkWasCreated('http://www.example.com?param=###my_custom_field###');
        }
    }

    /**
     * Assert that there is exactly 1 record corresponding to the given URL
     * @param string $url
     */
    private function assertLinkWasCreated($url)
    {
        $db = $this->getDatabaseConnection();
        $count = $db->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_link', 'url = ' . $db->fullQuoteStr($url, 'tx_newsletter_domain_model_link'));
        $this->assertSame(1, $count, 'could not find exactly 1 log record containing "' . $url . '"');
    }

    /**
     * Replace random parts of email with non-random string
     * @param string $email
     * @return string
     */
    private function unrandomizeEmail($email)
    {
        $notRandoms = [];
        $unRandomize = function ($matched) use (&$notRandoms) {
            $random = $matched[0];
            if (!isset($notRandoms[$random])) {
                $notRandoms[$random] = 'NOT_RANDOM_' . count($notRandoms);
            }

            return $notRandoms[$random];
        };

        $randomPatterns = [
            'Date: .*',
            '_=_swift_v[\da-f_]+_=_',
            '[\da-f\n\r=]+@swift.generated',
        ];

        $unRandomizedEmail = preg_replace_callback('/' . implode('|', $randomPatterns) . '/', $unRandomize, $email);

        // Sort some headers because order varies between PHP 5.6 VS PHP 7
        $sort = function ($matches) {
            // Join multi-lines headers in single line and split each headers
            $lines = explode("\r\n", trim(str_replace("\r\n ", ' ', $matches[0])));
            sort($lines);

            return implode("\r\n", $lines) . "\r\n";
        };

        $headerPatterns = [
            'Content-.*\r\n',
            'Precedence: bulk\r\n',
            'List-Unsubscribe: .*\r\n.*\r\n',
        ];
        $sortedEmail = preg_replace_callback('/((' . implode(')|(', $headerPatterns) . ')){2,}/', $sort, $unRandomizedEmail);

        return $sortedEmail;
    }
}

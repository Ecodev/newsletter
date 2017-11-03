<?php

namespace Ecodev\Newsletter\Tests\Unit\Domain\Model\PlainConverter;

use Ecodev\Newsletter\Domain\Model\PlainConverter\Lynx;
use Ecodev\Newsletter\Tools;

/**
 * Test case for class \Ecodev\Newsletter\Domain\Model\PlainConverter\Lynx.
 */
class LynxTest extends \Ecodev\Newsletter\Tests\Unit\AbstractUnitTestCase
{
    /**
     * @var Lynx
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = new Lynx();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    private function canRunLynx()
    {
        $this->loadConfiguration();

        $cmd = escapeshellcmd(Tools::confParam('path_to_lynx')) . ' --help';
        exec($cmd, $output, $statusCode);

        return $statusCode == 0;
    }

    /**
     * @test
     */
    public function getUrlReturnsInitialValueForString()
    {
        if (!$this->canRunLynx()) {
            $this->markTestSkipped('The command "' . Tools::confParam('path_to_lynx') . '" is not available.');
        }

        $html = file_get_contents(__DIR__ . '/input.html');
        $expected = file_get_contents(__DIR__ . '/lynx.txt');
        $actual = $this->subject->getPlainText($html, 'http://my-domain.com');
        $this->assertSame($expected, $actual);
    }
}

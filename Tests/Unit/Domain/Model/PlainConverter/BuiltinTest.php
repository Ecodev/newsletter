<?php

namespace Ecodev\Newsletter\Tests\Unit\Domain\Model\PlainConverter;

use Ecodev\Newsletter\Domain\Model\PlainConverter\Builtin;

/**
 * Test case for class \Ecodev\Newsletter\Domain\Model\PlainConverter\Builtin.
 */
class BuiltinTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var Builtin
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = new Builtin();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getUrlReturnsInitialValueForString()
    {
        $html = file_get_contents(__DIR__ . '/input.html');
        $expected = file_get_contents(__DIR__ . '/builtin.txt');
        $actual = $this->subject->getPlainText($html, 'http://my-domain.com');
        $this->assertSame($expected, $actual);
    }
}

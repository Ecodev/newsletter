<?php

namespace Ecodev\Newsletter\Tests\Functional\Utility;

use Ecodev\Newsletter\Utility\UriBuilder;
use ReflectionClass;

/**
 * Test case for class Ecodev\Newsletter\Utility\UriBuilder.
 */
class UriBuilderTest extends \Ecodev\Newsletter\Tests\Functional\AbstractFunctionalTestCase
{
    public function testBuildFrontendUriFromTca()
    {
        $actual = UriBuilder::buildFrontendUriFromTca('foo', 'bar', ['a' => 1, 'b' => 'baz']);
        $expected = '/?tx_newsletter_p%5Ba%5D=1&tx_newsletter_p%5Bb%5D=baz&tx_newsletter_p%5Baction%5D=bar&tx_newsletter_p%5Bcontroller%5D=foo&type=1342671779';
        $this->assertSame($expected, $actual);
    }

    public function testBuildFrontendUri()
    {
        // Create an internal builder mock
        $mockBuilder = $this->getMock(\TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder::class, ['buildFrontendUri'], [], '', false);
        $mockBuilder->method('buildFrontendUri')->will($this->onConsecutiveCalls('url1?a=1', 'url2?a=1'));

        // Inject the mock
        $reflectionClass = new ReflectionClass(UriBuilder::class);
        $property = $reflectionClass->getProperty('uriBuilder');
        $property->setAccessible(true);
        $property->setValue([1 => $mockBuilder]);

        $actual1 = UriBuilder::buildFrontendUri(1, 'foo', 'bar', ['a' => 1, 'l' => 'original']);
        $expectedArguments = [
            'tx_newsletter_p' => [
                'a' => 1,
                'action' => 'bar',
                'controller' => 'foo',
            ],
        ];
        $this->assertSame($expectedArguments, $mockBuilder->getArguments(), 'the internal builder should have been given namespaced arguments without the special l parameter');

        $actual2 = UriBuilder::buildFrontendUri(1, 'foo', 'bar', ['baz' => 1]);
        $actual1bis = UriBuilder::buildFrontendUri(1, 'foo', 'bar', ['a' => 1, 'l' => 'bis']);

        $this->assertSame('url1?a=1&tx_newsletter_p%5Bl%5D=original', $actual1, 'should be able to build URL with special l parameter');
        $this->assertSame('url2?a=1', $actual2, 'should be able to build URL without special l parameter');
        $this->assertSame('url1?a=1&tx_newsletter_p%5Bl%5D=bis', $actual1bis, 'should be able to hit the cache to retrieve the first URL and complete new value of l parameter');
    }
}

<?php

namespace Ecodev\Newsletter\Tests\Functional\Utility;

use Ecodev\Newsletter\Utility\UriBuilder;

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
}

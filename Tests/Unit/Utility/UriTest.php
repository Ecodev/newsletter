<?php

namespace Ecodev\Newsletter\Tests\Unit\Utility;

use Ecodev\Newsletter\Utility\Uri;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * Test case for class Ecodev\Newsletter\Utility\Uri.
 */
class UriTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    public function dataProviderTestUri()
    {
        $result = [];
        foreach (Uri::getSchemes() as $scheme) {
            $result[] = [$scheme];
        }

        return $result;
    }

    /**
     * @dataProvider dataProviderTestUri
     */
    public function testKnownSchemes($scheme)
    {
        $this->assertTrue(Uri::isAbsolute($scheme . ':foo/bar'));
        $this->assertTrue(Uri::isAbsolute($scheme . '://foo/bar'));
        $this->assertFalse(Uri::isAbsolute('foo/bar/' . $scheme));
    }

    public function testFragment()
    {
        $this->assertTrue(Uri::isAbsolute('http:foo/bar#abc'));
        $this->assertTrue(Uri::isAbsolute('#abc'), 'fragment URL should be considered absolute URI because they msut not be modified at all');
    }
}

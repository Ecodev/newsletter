<?php

namespace Ecodev\Newsletter\Tests\Unit\Utility;

use Ecodev\Newsletter\Utility\EmailParser;

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
 * Test case for class Ecodev\Newsletter\Utility\EmailParser.
 */
class EmailParserTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    public function dataProviderTestParser()
    {
        $pattern = dirname(__DIR__) . '/Fixtures/bounce/*.eml';
        $result = [];
        foreach (glob($pattern) as $filename) {
            $parts = explode('-', basename($filename));
            $result[] = [
                (int) $parts[0],
                $parts[1]? : null,
                $parts[2],
                $filename,
            ];
        }

        return $result;
    }

    /**
     * @dataProvider dataProviderTestParser
     */
    public function testParser($expectedBounce, $expectedAuthCode, $message, $filename)
    {
        $emailSource = file_get_contents($filename);
        $parser = new EmailParser();
        $parser->parse($emailSource);
        $this->assertSame($expectedBounce, $parser->getBounceLevel(), 'Bounce level should be ' . var_export($expectedBounce, true) . ' for ' . $message);
        $this->assertSame($expectedAuthCode, $parser->getAuthCode(), 'AuthCode should be ' . var_export($expectedAuthCode, true) . ' for ' . $message);
    }
}

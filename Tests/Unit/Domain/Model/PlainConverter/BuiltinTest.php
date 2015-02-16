<?php

namespace Ecodev\Newsletter\Tests\Unit\Domain\Model\PlainConverter;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015
 *
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
 * Test case for class \Ecodev\Newsletter\Domain\Model\Builtin.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class BuiltinTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Ecodev\Newsletter\Domain\Model\Builtin
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = new \Ecodev\Newsletter\Domain\Model\PlainConverter\Builtin();
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
        $this->assertEquals($expected, $actual);
    }
}

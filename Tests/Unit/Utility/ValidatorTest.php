<?php

namespace Ecodev\Newsletter\Tests\Unit\Utility;

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
 * Test case for class Ecodev\Newsletter\Utility\Validator.
 */
class ValidatorTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Ecodev\Newsletter\Utility\Validator
     */
    private $validator = null;

    protected function setUp()
    {
        global $LANG;

        // Initialize a fake LANG that return the localisation key instead of real value
        $LANG = $this->getMock('TYPO3\\CMS\\Lang\\LanguageService', array('includeLLFile', 'getLL'), array(), '', false);
        $LANG->method('includeLLFile')->will($this->returnValue(null));
        $LANG->method('getLL')->will($this->returnCallback(function ($langKey) {
                    return $langKey;
                }));

        $this->validator = $this->getMock('Ecodev\\Newsletter\\Utility\\Validator', array('getURL'), array(), '', false);
        $this->newsletter = $this->getMock('Ecodev\\Newsletter\\Domain\\Model\\Newsletter', array('getContentUrl', 'getDomain'), array(), '', false);
        $this->newsletter->method('getContentUrl')->will($this->returnValue('http://example.com/?id=123'));
        $this->newsletter->method('getDomain')->will($this->returnValue('example.com'));
    }

    protected function tearDown()
    {
        unset($this->validator);
    }

    public function dataProviderTestValidator()
    {
        $result = array(
            array(
                '<a href="relative-url">link</a>',
                array(
                    'content' => '<a href="http://example.com/relative-url">link</a>',
                    'errors' => array('validation_mail_too_short'),
                    'warnings' => array(),
                    'infos' => array(
                        'validation_content_url',
                        'validation_mail_converted_relative_url',
                    ),
                ),
            ),
            array(
                '<a href="http://other-domain.com/absolute-url">link</a>',
                array(
                    'content' => '<a href="http://other-domain.com/absolute-url">link</a>',
                    'errors' => array('validation_mail_too_short'),
                    'warnings' => array(),
                    'infos' => array(
                        'validation_content_url',
                    ),
                ),
            ),
            array(
                '<a href="https://other-domain.com/absolute-url">link</a>',
                array(
                    'content' => '<a href="https://other-domain.com/absolute-url">link</a>',
                    'errors' => array('validation_mail_too_short'),
                    'warnings' => array(),
                    'infos' => array(
                        'validation_content_url',
                    ),
                ),
            ),
            array(
                '<a href="ftp://other-domain.com/absolute-url">link</a>',
                array(
                    'content' => '<a href="ftp://other-domain.com/absolute-url">link</a>',
                    'errors' => array('validation_mail_too_short'),
                    'warnings' => array(),
                    'infos' => array(
                        'validation_content_url',
                    ),
                ),
            ),
            array(
                '<a href="mailto:john@example.com">email</a>',
                array(
                    'content' => '<a href="mailto:john@example.com">email</a>',
                    'errors' => array('validation_mail_too_short'),
                    'warnings' => array(),
                    'infos' => array(
                        'validation_content_url',
                    ),
                ),
            ),
            array(
                '<a href="&#x6D;&#x61;&#x69;&#x6C;&#x74;&#x6F;&#x3A;&#x6A;&#x6F;&#x68;&#x6E;&#x40;&#x65;&#x78;&#x61;&#x6D;&#x70;&#x6C;&#x65;&#x2E;&#x63;&#x6F;&#x6D;">encoded email</a>',
                array(
                    'content' => '<a href="&#x6D;&#x61;&#x69;&#x6C;&#x74;&#x6F;&#x3A;&#x6A;&#x6F;&#x68;&#x6E;&#x40;&#x65;&#x78;&#x61;&#x6D;&#x70;&#x6C;&#x65;&#x2E;&#x63;&#x6F;&#x6D;">encoded email</a>',
                    'errors' => array('validation_mail_too_short'),
                    'warnings' => array(),
                    'infos' => array(
                        'validation_content_url',
                    ),
                ),
            ),
            array(
                '<img src="relative.jpg"/>',
                array(
                    'content' => '<img src="http://example.com/relative.jpg"/>',
                    'errors' => array('validation_mail_too_short'),
                    'warnings' => array(),
                    'infos' => array(
                        'validation_content_url',
                        'validation_mail_converted_relative_url',
                    ),
                ),
            ),
            array(
                '<img src="http://other-domain.com/absolute.jpg"/>',
                array(
                    'content' => '<img src="http://other-domain.com/absolute.jpg"/>',
                    'errors' => array('validation_mail_too_short'),
                    'warnings' => array(),
                    'infos' => array(
                        'validation_content_url',
                    ),
                ),
            ),
            array(
                '<script type="text/javascript"> window.brokenIE = true; </script>',
                array(
                    'content' => '',
                    'errors' => array('validation_mail_too_short'),
                    'warnings' => array('validation_mail_contains_javascript'),
                    'infos' => array(
                        'validation_content_url',
                    ),
                ),
            ),
            array(
                '<span style="background-image: url(\'image.jpg\');"></span>',
                array(
                    'content' => '<span style="background-image: url(\'image.jpg\');"></span>',
                    'errors' => array('validation_mail_too_short', 'validation_mail_contains_css_images'),
                    'warnings' => array(),
                    'infos' => array(
                        'validation_content_url',
                    ),
                ),
            ),
            array(
                '<ul style="list-style-image: url(\'image.jpg\');"></ul>',
                array(
                    'content' => '<ul style="list-style-image: url(\'image.jpg\');"></ul>',
                    'errors' => array('validation_mail_too_short', 'validation_mail_contains_css_images'),
                    'warnings' => array(),
                    'infos' => array(
                        'validation_content_url',
                    ),
                ),
            ),
            array(
                '<p class="bigger"><p>',
                array(
                    'content' => '<p class="bigger"><p>',
                    'errors' => array('validation_mail_too_short'),
                    'warnings' => array('validation_mail_contains_css_classes'),
                    'infos' => array(
                        'validation_content_url',
                    ),
                ),
            ),
        );

        $forbiddenCssProperties = array(
            false => array(
                'border-width',
                'padding-block-end',
            ),
            true => array(
                'width', 'min-width', 'max-width',
                'height', 'min-height', 'max-height',
                'padding', 'padding-bottom', 'padding-left', 'padding-right', 'padding-top',
                'margin', 'margin-bottom', 'margin-left', 'margin-right', 'margin-top',
                'position',
            ),
        );

        // Generate additionnal test cases for CSS properties
        foreach ($forbiddenCssProperties as $isForbidden => $properties) {
            foreach ($properties as $property) {

                // First property
                $result[] = array(
                    '<p style="' . $property . ': 10px"><p>',
                    array(
                        'content' => '<p style="' . $property . ': 10px"><p>',
                        'errors' => array('validation_mail_too_short'),
                        'warnings' => $isForbidden ? array('validation_mail_contains_css_some_property') : array(),
                        'infos' => array(
                            'validation_content_url',
                        ),
                    ),
                );

                // In the middle
                $result[] = array(
                    '<p style="color: red;' . $property . ': 10px"><p>',
                    array(
                        'content' => '<p style="color: red;' . $property . ': 10px"><p>',
                        'errors' => array('validation_mail_too_short'),
                        'warnings' => $isForbidden ? array('validation_mail_contains_css_some_property') : array(),
                        'infos' => array(
                            'validation_content_url',
                        ),
                    ),
                );

                // In the middle, but with a space
                $result[] = array(
                    '<p style="color: red; ' . $property . ': 10px"><p>',
                    array(
                        'content' => '<p style="color: red; ' . $property . ': 10px"><p>',
                        'errors' => array('validation_mail_too_short'),
                        'warnings' => $isForbidden ? array('validation_mail_contains_css_some_property') : array(),
                        'infos' => array(
                            'validation_content_url',
                        ),
                    ),
                );
            }
        }

        return $result;
    }

    /**
     * @dataProvider dataProviderTestValidator
     */
    public function testValidator($input, $expected)
    {
        $this->validator->method('getURL')->will($this->returnValue($input));
        $actual = $this->validator->validate($this->newsletter);
        $this->assertSame($expected, $actual);
    }
}

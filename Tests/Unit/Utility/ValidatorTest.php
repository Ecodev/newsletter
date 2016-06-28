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
        $LANG = $this->getMock(\TYPO3\CMS\Lang\LanguageService::class, ['includeLLFile', 'getLL'], [], '', false);
        $LANG->method('includeLLFile')->will($this->returnValue(null));
        $LANG->method('getLL')->will($this->returnCallback(function ($langKey) {
                    return $langKey;
                }));

        $this->validator = $this->getMock(\Ecodev\Newsletter\Utility\Validator::class, ['getURL'], [], '', false);
        $this->newsletter = $this->getMock(\Ecodev\Newsletter\Domain\Model\Newsletter::class, ['getContentUrl', 'getBaseUrl'], [], '', false);
        $this->newsletter->method('getContentUrl')->will($this->returnValue('http://example.com/?id=123'));
        $this->newsletter->method('getBaseUrl')->will($this->returnValue('http://example.com'));
    }

    protected function tearDown()
    {
        unset($this->validator);
    }

    public function dataProviderTestValidator()
    {
        $result = [
            [
                '<a href="relative-url">link</a>',
                [
                    'content' => '<a href="http://example.com/relative-url">link</a>',
                    'errors' => ['validation_mail_too_short'],
                    'warnings' => [],
                    'infos' => [
                        'validation_content_url',
                        'validation_mail_converted_relative_url',
                    ],
                ],
            ],
            [
                '<a href="http://other-domain.com/absolute-url">link</a>',
                [
                    'content' => '<a href="http://other-domain.com/absolute-url">link</a>',
                    'errors' => ['validation_mail_too_short'],
                    'warnings' => [],
                    'infos' => [
                        'validation_content_url',
                    ],
                ],
            ],
            [
                '<a href="https://other-domain.com/absolute-url">link</a>',
                [
                    'content' => '<a href="https://other-domain.com/absolute-url">link</a>',
                    'errors' => ['validation_mail_too_short'],
                    'warnings' => [],
                    'infos' => [
                        'validation_content_url',
                    ],
                ],
            ],
            [
                '<a href="ftp://other-domain.com/absolute-url">link</a>',
                [
                    'content' => '<a href="ftp://other-domain.com/absolute-url">link</a>',
                    'errors' => ['validation_mail_too_short'],
                    'warnings' => [],
                    'infos' => [
                        'validation_content_url',
                    ],
                ],
            ],
            [
                '<a href="mailto:john@example.com">email</a>',
                [
                    'content' => '<a href="mailto:john@example.com">email</a>',
                    'errors' => ['validation_mail_too_short'],
                    'warnings' => [],
                    'infos' => [
                        'validation_content_url',
                    ],
                ],
            ],
            [
                '<a href="&#x6D;&#x61;&#x69;&#x6C;&#x74;&#x6F;&#x3A;&#x6A;&#x6F;&#x68;&#x6E;&#x40;&#x65;&#x78;&#x61;&#x6D;&#x70;&#x6C;&#x65;&#x2E;&#x63;&#x6F;&#x6D;">encoded email</a>',
                [
                    'content' => '<a href="mailto:john@example.com">encoded email</a>',
                    'errors' => ['validation_mail_too_short'],
                    'warnings' => [],
                    'infos' => [
                        'validation_content_url',
                    ],
                ],
            ],
            [
                '<img src="relative.jpg" alt="relative">',
                [
                    'content' => '<img src="http://example.com/relative.jpg" alt="relative">',
                    'errors' => ['validation_mail_too_short'],
                    'warnings' => [],
                    'infos' => [
                        'validation_content_url',
                        'validation_mail_converted_relative_url',
                    ],
                ],
            ],
            [
                '<img src="http://other-domain.com/absolute.jpg" alt="absolute">',
                [
                    'content' => '<img src="http://other-domain.com/absolute.jpg" alt="absolute">',
                    'errors' => ['validation_mail_too_short'],
                    'warnings' => [],
                    'infos' => [
                        'validation_content_url',
                    ],
                ],
            ],
            [
                '<script type="text/javascript"> window.brokenIE = true; </script>',
                [
                    'content' => '',
                    'errors' => ['validation_mail_too_short'],
                    'warnings' => ['validation_mail_contains_javascript'],
                    'infos' => [
                        'validation_content_url',
                    ],
                ],
            ],
            [
                '<span style="background-image: url(\'image.jpg\');"></span>',
                [
                    'content' => '<span style="background-image: url(\'image.jpg\');"></span>',
                    'errors' => ['validation_mail_too_short', 'validation_mail_contains_css_images'],
                    'warnings' => [],
                    'infos' => [
                        'validation_content_url',
                    ],
                ],
            ],
            [
                '<ul style="list-style-image: url(\'image.jpg\');"></ul>',
                [
                    'content' => '<ul style="list-style-image: url(\'image.jpg\');"></ul>',
                    'errors' => ['validation_mail_too_short', 'validation_mail_contains_css_images'],
                    'warnings' => [],
                    'infos' => [
                        'validation_content_url',
                    ],
                ],
            ],
            [
                '<p class="bigger"></p>',
                [
                    'content' => '<p class="bigger"></p>',
                    'errors' => ['validation_mail_too_short'],
                    'warnings' => ['validation_mail_contains_css_classes'],
                    'infos' => [
                        'validation_content_url',
                    ],
                ],
            ],
            [
                '<p><img src="http://example.com/no-alt.png"><img src="http://example.com/empty-alt.png" alt=""><img src="http://example.com/alt.png" alt="some text"><svg>unkown tag</svg></p>',
                [
                    'content' => '<p><img src="http://example.com/no-alt.png" alt=""><img src="http://example.com/empty-alt.png" alt=""><img src="http://example.com/alt.png" alt="some text"><svg>unkown tag</svg></p>',
                    'errors' => ['validation_mail_too_short'],
                    'warnings' => [],
                    'infos' => [
                        'validation_content_url',
                        'validation_mail_injected_alt_attribute',
                    ],
                ],
            ],
        ];

        $forbiddenCssProperties = [
            false => [
                'border-width',
                'padding-block-end',
            ],
            true => [
                'width', 'min-width', 'max-width',
                'height', 'min-height', 'max-height',
                'padding', 'padding-bottom', 'padding-left', 'padding-right', 'padding-top',
                'margin', 'margin-bottom', 'margin-left', 'margin-right', 'margin-top',
                'position',
            ],
        ];

        // Generate additionnal test cases for CSS properties
        foreach ($forbiddenCssProperties as $isForbidden => $properties) {
            foreach ($properties as $property) {

                // First property
                $result[] = [
                    '<p style="' . $property . ': 10px"></p>',
                    [
                        'content' => '<p style="' . $property . ': 10px"></p>',
                        'errors' => ['validation_mail_too_short'],
                        'warnings' => $isForbidden ? ['validation_mail_contains_css_some_property'] : [],
                        'infos' => [
                            'validation_content_url',
                        ],
                    ],
                ];

                // In the middle
                $result[] = [
                    '<p style="color: red;' . $property . ': 10px"></p>',
                    [
                        'content' => '<p style="color: red;' . $property . ': 10px"></p>',
                        'errors' => ['validation_mail_too_short'],
                        'warnings' => $isForbidden ? ['validation_mail_contains_css_some_property'] : [],
                        'infos' => [
                            'validation_content_url',
                        ],
                    ],
                ];

                // In the middle, but with a space
                $result[] = [
                    '<p style="color: red; ' . $property . ': 10px"></p>',
                    [
                        'content' => '<p style="color: red; ' . $property . ': 10px"></p>',
                        'errors' => ['validation_mail_too_short'],
                        'warnings' => $isForbidden ? ['validation_mail_contains_css_some_property'] : [],
                        'infos' => [
                            'validation_content_url',
                        ],
                    ],
                ];
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

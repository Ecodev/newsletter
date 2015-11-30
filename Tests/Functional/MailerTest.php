<?php

namespace Ecodev\Newsletter\Tests\Functional;

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
 * Test case for class Ecodev\Newsletter\Mailer.
 */
class MailerTest extends \Ecodev\Newsletter\Tests\Functional\AbstractFunctionalTestCase
{
    /**
     * @var \Ecodev\Newsletter\Domain\Model\Newsletter
     */
    private $mockNewsletter = null;

    /**
     * @var \Ecodev\Newsletter\Domain\Model\Email
     */
    private $mockEmail = null;

    public function setUp()
    {
        parent::setUp();

        $this->mockNewsletter = $this->getMock('Ecodev\\Newsletter\\Domain\\Model\\Newsletter', array('getUid', 'getPid', 'getBaseUrl', 'getSenderName', 'getSenderEmail', 'getValidatedContent', 'getInjectOpenSpy', 'getInjectLinksSpy'), array(), '', false);
        $this->mockNewsletter->method('getUid')->will($this->returnValue(12345));
        $this->mockNewsletter->method('getPid')->will($this->returnValue(789));
        $this->mockNewsletter->method('getBaseUrl')->will($this->returnValue('http://example.com'));
        $this->mockNewsletter->method('getSenderName')->will($this->returnValue('John Connor'));
        $this->mockNewsletter->method('getSenderEmail')->will($this->returnValue('noreply@example.com'));

        $this->mockEmail = $this->getMock('Ecodev\\Newsletter\\Domain\\Model\\Email', array('s'), array(), '', false);
        $this->mockEmail->setRecipientData(array(
            'email' => 'recipient@example.com',
            'my_custom_field' => 'my custom value',
            'boolean_false' => false,
            'boolean_true' => true,
            'integer_false' => 0,
            'integer_true' => 1,
            'string_false' => '',
            'string_true' => 'foo',
        ));

        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['newsletter'] = serialize(array('attach_images' => true));
    }

    public function dataProviderTestMailer()
    {
        $data = array();
        foreach (glob(__DIR__ . '/Fixtures/mailer/*', GLOB_ONLYDIR) as $folder) {
            foreach (array(false, true) as $injectLinksSpy) {
                foreach (array(false, true) as $injectOpenSpy) {
                    $flags = var_export($injectOpenSpy, true) . '-' . var_export($injectLinksSpy, true);
                    $data[] = array(
                        $injectOpenSpy,
                        $injectLinksSpy,
                        $folder . '/input.html',
                        $folder . "/output-$flags.html",
                        $folder . "/output-$flags.txt",
                    );
                }
            }
        }

        return $data;
    }

    /**
     * @dataProvider dataProviderTestMailer
     */
    public function testMailer($injectOpenSpy, $injectLinksSpy, $inputFile, $expectedHtmlFile, $expectedPlainFile)
    {
        $input = file_get_contents($inputFile);
        $expectedHtml = file_get_contents($expectedHtmlFile);
        $expectedPlain = file_get_contents($expectedPlainFile);

        $this->mockNewsletter->method('getValidatedContent')->will($this->returnValue(
                        array(
                            'content' => $input,
                            'errors' => array(),
                            'warnings' => array(),
                            'infos' => array(),
                        )
        ));
        $this->mockNewsletter->method('getInjectOpenSpy')->will($this->returnValue($injectOpenSpy));
        $this->mockNewsletter->method('getInjectLinksSpy')->will($this->returnValue($injectLinksSpy));

        $mailer = $this->objectManager->get('Ecodev\\Newsletter\\Mailer');

        $mailer->setNewsletter($this->mockNewsletter);
        $mailer->prepare($this->mockEmail);

        $actualHtml = $mailer->getHtml();
        $actualPlain = $mailer->getPlain();
        $this->assertSame($expectedHtml, $actualHtml);
        $this->assertSame($expectedPlain, $actualPlain);

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
    protected function assertLinkWasCreated($url)
    {
        $db = $this->getDatabaseConnection();
        $count = $db->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_link', 'url = ' . $db->fullQuoteStr($url, 'tx_newsletter_domain_model_link'));
        $this->assertSame(1, $count, 'could not find exactly 1 log record containing "' . $url . '"');
    }
}

<?php

namespace Ecodev\Newsletter\Tests\Functional;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
require_once __DIR__ . '/AbstractFunctionalTestCase.php';
//require_once __DIR__ . '/../../Classes/Domain/Model/Newsletter.php';

/**
 * Functional test for the \Ecodev\Newsletter\Tools
 */
class ToolsTest extends \Ecodev\Newsletter\Tests\Functional\AbstractFunctionalTestCase
{
    public function testCreateAllSpool()
    {
        $this->importDataSet(ORIGINAL_ROOT . 'typo3/sysext/core/Tests/Functional/Fixtures/tt_content.xml');

        $db = $this->getDatabaseConnection();
        $count = $db->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_newsletter', 'begin_time != 0 AND end_time != 0');
        $this->assertEquals(0, $count);

        \Ecodev\Newsletter\Tools::createAllSpool();

        $count = $db->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_newsletter', 'begin_time != 0 AND end_time != 0');
        $this->assertEquals(1, $count, 'newsletter should be marked as spooled');

        $count = $db->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'newsletter = 20 AND begin_time = 0');
        $this->assertEquals(2, $count, 'two emails must have been created but not sent yet');

        // Prepare a mock to always validate content
        $mockValidator = $this->getMock('Ecodev\\Newsletter\\Utility\\Validator', array('validate'), array(), '', false);
        $mockValidator->method('validate')->will($this->returnValue(
                        array(
                            'content' => 'some very interesting content <a href="http://example.com/fake-content">link</a>',
                            'errors' => array(),
                            'warnings' => array(),
                            'infos' => array(),
                        )
        ));

        // Force email to NOT be sent
        global $TYPO3_CONF_VARS;
        $TYPO3_CONF_VARS['MAIL']['transport'] = 'Swift_NullTransport';

        $newsletterRepository = $this->objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\NewsletterRepository');
        $newsletter = $newsletterRepository->findByUid(20);
        $newsletter->setValidator($mockValidator);
        \Ecodev\Newsletter\Tools::runSpoolOne($newsletter);

        $count = $db->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'newsletter = 20 AND begin_time != 0 AND end_time != 0 AND recipient_data != ""');
        $this->assertEquals(2, $count, 'should have sent two emails');
        $count = $db->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_link', 'newsletter = 20');
        $this->assertEquals(1, $count, 'should have on1 new link');
    }
}

<?php

namespace Ecodev\Newsletter\Tests\Functional;

abstract class AbstractFunctionalTestCase extends \TYPO3\CMS\Core\Tests\FunctionalTestCase
{
    /** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
    protected $objectManager;
    protected $testExtensionsToLoad = array('typo3conf/ext/newsletter');
    protected $coreExtensionsToLoad = array('extbase', 'fluid');

    /**
     * Auth code for recipient 2
     * @var string
     */
    protected $authCode;

    public function setUp()
    {
        parent::setUp();
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

        $this->importDataSet(ORIGINAL_ROOT . 'typo3/sysext/core/Tests/Functional/Fixtures/pages.xml');
        $this->importDataSet(__DIR__ . '/Fixtures/fixtures.xml');
        $this->authCode = md5(302 . 'recipient2@example.com');
    }

    /**
     * Assert that there is exactly 1 record in sys_log table containing
     * the exact text given in $details
     * @param string $details
     */
    protected function assertRecipientListCallbackWasCalled($details)
    {
        $db = $this->getDatabaseConnection();
        $count = $db->exec_SELECTcountRows('*', 'sys_log', 'details = ' . $db->fullQuoteStr($details, 'sys_log'));
        $this->assertEquals(1, $count, 'could not find exactly 1 log record containing "' . $details . '"');
    }

}

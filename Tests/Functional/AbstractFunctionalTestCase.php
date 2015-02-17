<?php

namespace Ecodev\Newsletter\Tests\Functional;

abstract class AbstractFunctionalTestCase extends \TYPO3\CMS\Core\Tests\FunctionalTestCase
{
    /** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
    protected $objectManager;
    protected $testExtensionsToLoad = array('typo3conf/ext/newsletter');
    protected $coreExtensionsToLoad = array('extbase', 'fluid');

    public function setUp()
    {
        parent::setUp();
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

        $this->importDataSet(ORIGINAL_ROOT . 'typo3/sysext/core/Tests/Functional/Fixtures/pages.xml');
        $this->importDataSet(__DIR__ . '/Fixtures/fixtures.xml');
    }
}

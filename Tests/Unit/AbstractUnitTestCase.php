<?php

namespace Ecodev\Newsletter\Tests\Unit;

use TYPO3\CMS\Core\Configuration\ConfigurationManager;

class AbstractUnitTestCase extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    protected function loadConfiguration()
    {
        $manager = new ConfigurationManager();
        $path = $manager->getLocalConfigurationFileLocation();

        if (is_readable($path)) {
            $allConfig = $manager->getLocalConfiguration();
            $config = $allConfig['EXT']['extConf']['newsletter'];
        }

        if (!isset($config)) {
            $config = serialize([
                'path_to_lynx' => '/usr/bin/lynx',
                'replyto_name' => 'John Connor',
                'replyto_email' => 'john.connor@example.com',
            ]);
        }

        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['newsletter'] = $config;
    }
}

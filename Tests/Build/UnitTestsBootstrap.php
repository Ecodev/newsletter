<?php

require_once __DIR__ . '/../../../../../typo3/sysext/core/Build/UnitTestsBootstrap.php';

// This should not be necessary, but since TYPO3 7.4.0 it seems our test classes cannot be autoloaded anymore
spl_autoload_register(function ($class) {
    $prefix = 'Ecodev\\Newsletter\\Tests\\';
    if (strpos($class, $prefix) === 0) {
        $file = str_replace(array($prefix, '\\'), array('', '/'), $class) . '.php';

        require_once __DIR__ . '/../' . $file;
    }
});

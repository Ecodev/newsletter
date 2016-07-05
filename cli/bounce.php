<?php

require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('newsletter') . 'Classes/BounceHandler.php';

// If nothing is piped to this script, can't do anything
if (ftell(STDIN) === false) {
    throw new Exception('This script expects a raw email source to be piped from fetchmail');
}

// Read piped email raw source
$content = file_get_contents('php://stdin');

// Dispatch it to analyze its bounce level an take appropriate action
$bounceHandler = new \Ecodev\Newsletter\BounceHandler($content);
$bounceHandler->dispatch();

<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Includes typoscript files
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:newsletter/Configuration/TypoScript/setup.txt">');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:newsletter/Configuration/TypoScript/constants.txt">');

// Register keys for CLI
$TYPO3_CONF_VARS['SC_OPTIONS']['GLOBAL']['cliKeys']['newsletter_bounce'] = ['EXT:newsletter/cli/bounce.php', '_CLI_scheduler'];

// Configure FE plugin element
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Ecodev.' . $_EXTKEY, 'p', [// list of controller
    'Email' => 'show, opened',
    'Link' => 'clicked',
    'RecipientList' => 'unsubscribe, export',
], [// non-cacheable controller
        'Email' => 'show, opened, unsubscribe',
        'Link' => 'clicked',
        'RecipientList' => 'export',
    ]
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Ecodev\Newsletter\Task\SendEmails::class] = [
    'extension' => $_EXTKEY,
    'title' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang.xlf:task_send_emails_title',
    'description' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang.xlf:task_send_emails_description',
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Ecodev\Newsletter\Task\FetchBounces::class] = [
    'extension' => $_EXTKEY,
    'title' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang.xlf:task_fetch_bounces_title',
    'description' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang.xlf:task_fetch_bounces_description',
];

// Configure TCA custom eval and hooks to manage on-the-fly (de)encryption from database
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\Ecodev\Newsletter\Tca\BounceAccountTca::class] = 'EXT:' . $_EXTKEY . '/Classes/Tca/BounceAccountTca.php';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\Ecodev\Newsletter\Tca\BounceAccountDataProvider::class] = [
    'depends' => [
        \TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseEditRow::class,
    ],
];

// Make a call to update
if (TYPO3_MODE === 'BE') {
    $dispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
    $dispatcher->connect(\TYPO3\CMS\Extensionmanager\Utility\InstallUtility::class, 'afterExtensionInstall', \Ecodev\Newsletter\Update\Update::class, 'afterExtensionInstall');
}

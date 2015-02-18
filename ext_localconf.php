<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Includes typoscript files
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:newsletter/Configuration/TypoScript/setup.txt">');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:newsletter/Configuration/TypoScript/constants.txt">');

// Register keys for CLI
$TYPO3_CONF_VARS['SC_OPTIONS']['GLOBAL']['cliKeys']['newsletter_bounce'] = array('EXT:newsletter/cli/bounce.php', '_CLI_scheduler');

/**
 * Configure FE plugin element "TABLE"
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Ecodev.' . $_EXTKEY, 'p', array(// list of controller
    'Email' => 'show, opened',
    'Link' => 'clicked',
    'RecipientList' => 'unsubscribe, export',
        ), array(// non-cacheable controller
    'Email' => 'show, opened, unsubscribe',
    'Link' => 'clicked',
    'RecipientList' => 'export',
        )
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Ecodev\\Newsletter\\Task\\SendEmails'] = array(
    'extension' => $_EXTKEY,
    'title' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang.xlf:task_send_emails_title',
    'description' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang.xlf:task_send_emails_description',
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Ecodev\\Newsletter\\Task\\FetchBounces'] = array(
    'extension' => $_EXTKEY,
    'title' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang.xlf:task_fetch_bounces_title',
    'description' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang.xlf:task_fetch_bounces_description',
);

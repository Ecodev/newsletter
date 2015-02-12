<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Includes typoscript files
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:newsletter/Configuration/TypoScript/setup.txt">');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:newsletter/Configuration/TypoScript/constants.txt">');

// Register keys for CLI
$TYPO3_CONF_VARS['SC_OPTIONS']['GLOBAL']['cliKeys']['newsletter_spool_create'] = array('EXT:newsletter/cli/spool_create.php', '_CLI_newsletter');
$TYPO3_CONF_VARS['SC_OPTIONS']['GLOBAL']['cliKeys']['newsletter_spool_run'] = array('EXT:newsletter/cli/spool_run.php', '_CLI_newsletter');
$TYPO3_CONF_VARS['SC_OPTIONS']['GLOBAL']['cliKeys']['newsletter_bounce'] = array('EXT:newsletter/cli/bounce.php', '_CLI_newsletter');


/**
 * Configure FE plugin element "TABLE"
 */
Tx_Extbase_Utility_Extension::configurePlugin(
        $_EXTKEY, 'p', array(// list of controller
    'Email' => 'show, opened',
    'Link' => 'clicked',
    'RecipientList' => 'unsubscribe, export',
        ), array(// non-cacheable controller
    'Email' => 'show, opened, unsubscribe',
    'Link' => 'clicked',
    'RecipientList' => 'export',
        )
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Tx_Newsletter_Task_SendEmails'] = array(
    'extension' => $_EXTKEY,
    'title' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang.xlf:task_send_emails_title',
    'description' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang.xlf:task_send_emails_description',
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Tx_Newsletter_Task_FetchBounces'] = array(
    'extension' => $_EXTKEY,
    'title' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang.xlf:task_fetch_bounces_title',
    'description' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang.xlf:task_fetch_bounces_description',
);

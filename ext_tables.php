<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// ========== Register BE Modules
if (TYPO3_MODE == 'BE') {
    Tx_Extbase_Utility_Extension::registerModule(
            $_EXTKEY, 'web', // Make newsletter module a submodule of 'user'
            'tx_newsletter_m1', // Submodule key
            'before:info', // Position
            array(
        'Module' => 'index',
        'Newsletter' => 'list, listPlanned, create, statistics',
        'Email' => 'list',
        'Link' => 'list',
        'BounceAccount' => 'list',
        'RecipientList' => 'list, listRecipient',
            ), array(
        'access' => 'user,group',
        'icon' => 'EXT:newsletter/Resources/Public/Icons/tx_newsletter.png',
        'labels' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_module.xml',
            )
    );
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_newsletter_domain_model_newsletter', 'EXT:newsletter/Resources/Private/Language/locallang_csh_tx_newsletter_domain_model_newsletter.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_newsletter_domain_model_newsletter');
$TCA['tx_newsletter_domain_model_newsletter'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_newsletter',
        'label' => 'planned_time',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Newsletter.php',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_newsletter_domain_model_newsletter.gif'
    )
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_newsletter_domain_model_bounceaccount', 'EXT:newsletter/Resources/Private/Language/locallang_csh_tx_newsletter_domain_model_bounceaccount.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_newsletter_domain_model_bounceaccount');
$TCA['tx_newsletter_domain_model_bounceaccount'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_bounceaccount',
        'label' => 'email',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/BounceAccount.php',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_newsletter_domain_model_bounceaccount.gif'
    )
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_newsletter_domain_model_recipientlist', 'EXT:newsletter/Resources/Private/Language/locallang_csh_tx_newsletter_domain_model_recipientlist.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_newsletter_domain_model_recipientlist');
$TCA['tx_newsletter_domain_model_recipientlist'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'type' => 'type',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/RecipientList.php',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_newsletter_domain_model_recipientlist.gif',
        'type' => 'type' // this tells extbase to respect the "type" column for Single Table Inheritance
    )
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_newsletter_domain_model_email', 'EXT:newsletter/Resources/Private/Language/locallang_csh_tx_newsletter_domain_model_email.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_newsletter_domain_model_email');
$TCA['tx_newsletter_domain_model_email'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_email',
        'label' => 'recipient_address',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Email.php',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_newsletter_domain_model_email.gif'
    )
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_newsletter_domain_model_link', 'EXT:newsletter/Resources/Private/Language/locallang_csh_tx_newsletter_domain_model_link.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_newsletter_domain_model_link');
$TCA['tx_newsletter_domain_model_link'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_link',
        'label' => 'url',
        'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Link.php',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_newsletter_domain_model_link.gif'
    )
);

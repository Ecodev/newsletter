<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$TCA['tx_newsletter_domain_model_recipientlist'] = array(
    'ctrl' => $TCA['tx_newsletter_domain_model_recipientlist']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'hidden,title',
    ),
    'feInterface' => $TCA['tx_newsletter_domain_model_recipientlist']['feInterface'],
    'columns' => array(
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => array(
                'type' => 'check',
                'default' => '0',
            ),
        ),
        'title' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.title',
            'config' => array(
                'type' => 'input',
                'size' => '30',
                'eval' => 'trim,required',
            ),
        ),
        'plain_only' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.plain_only',
            'config' => array(
                'type' => 'check',
                'default' => '0',
            ),
        ),
        'lang' => array(
            'label' => 'LLL:EXT:lang/locallang_tca.php:sys_language',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.uid',
                'minitems' => 0,
                'maxitems' => 1,
                'items' => array(
                    '0' => array('', -1),
                    '1' => array('LLL:EXT:lang/locallang_general.php:LGL.default_value', 0),
                ),
            ),
        ),
        'be_users' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.be_users',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'be_users',
                'foreign_table_where' => 'ORDER BY be_users.uid',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
            ),
        ),
        'fe_groups' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.fe_groups',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'fe_groups',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
            ),
        ),
        'fe_pages' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.fe_pages',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
            ),
        ),
        'sql_statement' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.sql_statement',
            'config' => array(
                'type' => 'text',
                'cols' => '50',
                'rows' => '10',
            ),
        ),
        'sql_register_bounce' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.sql_register_bounce',
            'config' => array(
                'type' => 'text',
                'cols' => '50',
                'rows' => '10',
            ),
        ),
        'sql_register_open' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.sql_register_open',
            'config' => array(
                'type' => 'text',
                'cols' => '50',
                'rows' => '10',
            ),
        ),
        'sql_register_click' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.sql_register_click',
            'config' => array(
                'type' => 'text',
                'cols' => '50',
                'rows' => '10',
            ),
        ),
        'csv_separator' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.csv_separator',
            'config' => array(
                'type' => 'input',
                'size' => 1,
            ),
        ),
        'csv_fields' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.csv_fields',
            'config' => array(
                'type' => 'input',
                'size' => 20,
            ),
        ),
        'csv_values' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.csv_values',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 10,
            ),
        ),
        'csv_filename' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.csv_file',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'file',
                'allowed' => 'csv,txt',
                'max_size' => 500,
                'uploadfolder' => 'uploads/tx_newsletter',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
            ),
        ),
        'csv_url' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.csv_url',
            'config' => array(
                'type' => 'input',
                'size' => 20,
            ),
        ),
        'type' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type_be_users', 'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\BeUsers'),
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type_fe_groups', 'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\FeGroups'),
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type_fe_pages', 'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\FePages'),
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type_sql', 'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\Sql'),
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type_csv_file', 'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\CsvFile'),
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type_csv_list', 'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\CsvList'),
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type_csv_url', 'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\CsvUrl'),
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type_html', 'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\Html'),
                ),
                'maxitems' => 1,
                'default' => 'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\Sql',
            ),
        ),
        'html_url' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.html_url',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim,required',
            ),
        ),
        'html_fetch_type' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.html_fetch_type',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.html_fetch_type_mailto', 'mailto'),
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.html_fetch_type_regex', 'regex'),
                ),
                'size' => 1,
                'maxitems' => 1,
            ),
        ),
        'recipients_preview' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang.xlf:preview',
            'config' => array(
                'type' => 'user',
                'userFunc' => 'Ecodev\\Newsletter\Tca\\RecipientListTca->render',
            ),
        ),
    ),
    'types' => array(
        '0' => array('showitem' => 'hidden;;1, title, type'),
        'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\BeUsers' => array('showitem' => 'hidden;;1, title, plain_only, lang, type, be_users, recipients_preview'),
        'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\FeGroups' => array('showitem' => 'hidden;;1, title, plain_only, lang, type, fe_groups, recipients_preview'),
        'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\FePages' => array('showitem' => 'hidden;;1, title, plain_only, lang, type, fe_pages, recipients_preview'),
        'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\Sql' => array('showitem' => 'hidden;;1, title, plain_only, type, sql_statement, sql_register_bounce, sql_register_open, sql_register_click, recipients_preview'),
        'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\CsvFile' => array('showitem' => 'hidden;;1, title, plain_only, type, csv_separator, csv_fields, csv_filename, recipients_preview'),
        'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\CsvList' => array('showitem' => 'hidden;;1, title, plain_only, type, csv_separator, csv_fields, csv_values, recipients_preview'),
        'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\CsvUrl' => array('showitem' => 'hidden;;1, title, plain_only, type, csv_separator, csv_fields, csv_url, recipients_preview'),
        'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\Html' => array('showitem' => 'hidden;;1, title, plain_only, lang, type, html_url, html_fetch_type, recipients_preview'),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    ),
);

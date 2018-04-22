<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'iconfile' => \Ecodev\Newsletter\Tools::getIconfilePrefix() . 'Resources/Public/Icons/tx_newsletter_domain_model_recipientlist.gif',
        'type' => 'type', // this tells extbase to respect the "type" column for Single Table Inheritance
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden,title',
    ],
    'feInterface' => $TCA['tx_newsletter_domain_model_recipientlist']['feInterface'],
    'columns' => [
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => '0',
            ],
        ],
        'title' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.title',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'eval' => 'trim,required',
            ],
        ],
        'plain_only' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.plain_only',
            'config' => [
                'type' => 'check',
                'default' => '0',
            ],
        ],
        'lang' => [
            'label' => 'LLL:EXT:lang/locallang_tca.php:sys_language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.uid',
                'minitems' => 0,
                'maxitems' => 1,
                'items' => [
                    ['LLL:EXT:lang/locallang_general.php:LGL.default_value', 0],
                ],
            ],
        ],
        'be_users' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.be_users',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'be_users',
                'foreign_table_where' => 'ORDER BY be_users.uid',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
            ],
        ],
        'fe_groups' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.fe_groups',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'fe_groups',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
            ],
        ],
        'fe_pages' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.fe_pages',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
            ],
        ],
        'sql_statement' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.sql_statement',
            'config' => [
                'type' => 'text',
                'cols' => '50',
                'rows' => '10',
            ],
        ],
        'sql_register_bounce' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.sql_register_bounce',
            'config' => [
                'type' => 'text',
                'cols' => '50',
                'rows' => '10',
            ],
        ],
        'sql_register_open' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.sql_register_open',
            'config' => [
                'type' => 'text',
                'cols' => '50',
                'rows' => '10',
            ],
        ],
        'sql_register_click' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.sql_register_click',
            'config' => [
                'type' => 'text',
                'cols' => '50',
                'rows' => '10',
            ],
        ],
        'csv_separator' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.csv_separator',
            'config' => [
                'type' => 'input',
                'size' => 1,
            ],
        ],
        'csv_fields' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.csv_fields',
            'config' => [
                'type' => 'input',
                'size' => 20,
            ],
        ],
        'csv_values' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.csv_values',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 10,
            ],
        ],
        'csv_filename' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.csv_file',
            'config' => [
                'type' => 'group',
                'internal_type' => 'file',
                'allowed' => 'csv,txt',
                'max_size' => 500,
                'uploadfolder' => 'uploads/tx_newsletter',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'csv_url' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.csv_url',
            'config' => [
                'type' => 'input',
                'size' => 20,
            ],
        ],
        'type' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type_be_users', \Ecodev\Newsletter\Domain\Model\RecipientList\BeUsers::class],
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type_fe_groups', \Ecodev\Newsletter\Domain\Model\RecipientList\FeGroups::class],
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type_fe_pages', \Ecodev\Newsletter\Domain\Model\RecipientList\FePages::class],
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type_sql', \Ecodev\Newsletter\Domain\Model\RecipientList\Sql::class],
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type_csv_file', \Ecodev\Newsletter\Domain\Model\RecipientList\CsvFile::class],
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type_csv_list', \Ecodev\Newsletter\Domain\Model\RecipientList\CsvList::class],
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type_csv_url', \Ecodev\Newsletter\Domain\Model\RecipientList\CsvUrl::class],
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.type_html', \Ecodev\Newsletter\Domain\Model\RecipientList\Html::class],
                ],
                'maxitems' => 1,
                'default' => \Ecodev\Newsletter\Domain\Model\RecipientList\Sql::class,
            ],
        ],
        'html_url' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.html_url',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim,required',
            ],
        ],
        'html_fetch_type' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.html_fetch_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.html_fetch_type_mailto', 'mailto'],
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_recipientlist.html_fetch_type_regex', 'regex'],
                ],
                'size' => 1,
                'maxitems' => 1,
            ],
        ],
        'recipients_preview' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang.xlf:preview',
            'config' => [
                'type' => 'user',
                'userFunc' => 'Ecodev\\Newsletter\Tca\\RecipientListTca->render',
            ],
        ],
    ],
    'types' => [
        '0' => ['showitem' => 'hidden;;1, title, type'],
        \Ecodev\Newsletter\Domain\Model\RecipientList\BeUsers::class => ['showitem' => 'hidden;;1, title, plain_only, lang, type, be_users, recipients_preview'],
        \Ecodev\Newsletter\Domain\Model\RecipientList\FeGroups::class => ['showitem' => 'hidden;;1, title, plain_only, lang, type, fe_groups, recipients_preview'],
        \Ecodev\Newsletter\Domain\Model\RecipientList\FePages::class => ['showitem' => 'hidden;;1, title, plain_only, lang, type, fe_pages, recipients_preview'],
        \Ecodev\Newsletter\Domain\Model\RecipientList\Sql::class => ['showitem' => 'hidden;;1, title, plain_only, type, sql_statement, sql_register_bounce, sql_register_open, sql_register_click, recipients_preview'],
        \Ecodev\Newsletter\Domain\Model\RecipientList\CsvFile::class => ['showitem' => 'hidden;;1, title, plain_only, type, csv_separator, csv_fields, csv_filename, recipients_preview'],
        \Ecodev\Newsletter\Domain\Model\RecipientList\CsvList::class => ['showitem' => 'hidden;;1, title, plain_only, type, csv_separator, csv_fields, csv_values, recipients_preview'],
        \Ecodev\Newsletter\Domain\Model\RecipientList\CsvUrl::class => ['showitem' => 'hidden;;1, title, plain_only, type, csv_separator, csv_fields, csv_url, recipients_preview'],
        \Ecodev\Newsletter\Domain\Model\RecipientList\Html::class => ['showitem' => 'hidden;;1, title, plain_only, lang, type, html_url, html_fetch_type, recipients_preview'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
];

<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$TCA['tx_newsletter_domain_model_bounceaccount'] = [
    'ctrl' => $TCA['tx_newsletter_domain_model_bounceaccount']['ctrl'],
    'interface' => [
        'showRecordFieldList' => 'email,server,protocol,port,username,password',
    ],
    'types' => [
        '1' => ['showitem' => 'email,protocol,server,port,username,password,config'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'email' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_bounceaccount.email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'server' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_bounceaccount.server',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'protocol' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_bounceaccount.protocol',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['POP3', 'pop3'],
                    ['IMAP', 'imap'],
                ],
            ],
        ],
        'port' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_bounceaccount.port',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'int',
            ],
        ],
        'username' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_bounceaccount.username',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'password' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_bounceaccount.password',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'Ecodev\\Newsletter\\Tca\\BounceAccountTca',
            ],
        ],
        'config' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_bounceaccount.config',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 8,
                'eval' => 'Ecodev\\Newsletter\\Tca\\BounceAccountTca',
                'wrap' => 'off',
                'default' => "poll ###SERVER###\nproto ###PROTOCOL### \nport ###PORT###\nusername \"###USERNAME###\"\npassword \"###PASSWORD###\"\n",
            ],
        ],
    ],
];

<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_link',
        'label' => 'url',
        'iconfile' => 'EXT:newsletter/Resources/Public/Icons/tx_newsletter_domain_model_link.gif',
    ],
    'interface' => [
        'showRecordFieldList' => 'url,opened_count,newsletter',
    ],
    'types' => [
        '1' => ['showitem' => 'url,opened_count,newsletter'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
        'url' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_link.url',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim',
                'readOnly' => true,
            ],
        ],
        'opened_count' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_link.opened_count',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'readOnly' => true,
            ],
        ],
        'newsletter' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_link.newsletter',
            'config' => [
                'readOnly' => true,
                'type' => 'inline',
                'foreign_table' => 'tx_newsletter_domain_model_newsletter',
                'minitems' => 0,
                'maxitems' => 1,
                'appearance' => [
                    'collapse' => 0,
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                ],
            ],
        ],
    ],
];

<?php

// From TYPO3 7.4.0 onward we must use EXT prefix
if (version_compare(TYPO3_version, '7.4.0', '>=')) {
    $wizardIcon = 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_edit.gif';
} else {
    // But for TYPO3 6.2 family, we still have to use old style
    $wizardIcon = 'edit2.gif';
}

return [
    'ctrl' => [
        'title' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter',
        'label' => 'planned_time',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'iconfile' => \Ecodev\Newsletter\Tools::getIconfilePrefix() . 'Resources/Public/Icons/tx_newsletter_domain_model_newsletter.gif',
    ],
    'interface' => [
        'showRecordFieldList' => 'planned_time,begin_time,end_time,repetition,plain_converter,is_test,attachments,sender_name,sender_email,replyto_name,replyto_email,inject_open_spy,inject_links_spy,bounce_account,recipient_list',
    ],
    'types' => [
        '1' => ['showitem' => 'planned_time,begin_time,end_time,repetition,plain_converter,is_test,attachments,sender_name,sender_email,replyto_name,replyto_email,inject_open_spy,inject_links_spy,bounce_account,recipient_list'],
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
        'planned_time' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.planned_time',
            'config' => [
                'type' => 'input',
                'size' => 12,
                'eval' => 'datetime,required',
            ],
        ],
        'begin_time' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.begin_time',
            'config' => [
                'type' => 'input',
                'size' => 12,
                'readOnly' => true,
                'eval' => 'datetime',
            ],
        ],
        'end_time' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.end_time',
            'config' => [
                'type' => 'input',
                'size' => 12,
                'readOnly' => true,
                'eval' => 'datetime',
            ],
        ],
        'repetition' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition_none', '0'],
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition_daily', '1'],
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition_weekly', '2'],
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition_biweekly', '3'],
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition_monthly', '4'],
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition_quarterly', '5'],
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition_semiyearly', '6'],
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition_yearly', '7'],
                ],
                'maxitems' => 1,
            ],
        ],
        'plain_converter' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.plain_converter',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.plain_converter_builtin', \Ecodev\Newsletter\Domain\Model\PlainConverter\Builtin::class],
                    ['LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.plain_converter_lynx', \Ecodev\Newsletter\Domain\Model\PlainConverter\Lynx::class],
                ],
                'maxitems' => 1,
            ],
        ],
        'is_test' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.is_test',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'attachments' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.attachments',
            'config' => [
                'type' => 'group',
                'internal_type' => 'file',
                'allowed' => '',
                'disallowed' => 'php,php3',
                'max_size' => 500,
                'uploadfolder' => 'uploads/tx_newsletter',
                'size' => 3,
                'minitems' => 0,
                'maxitems' => 10,
            ],
        ],
        'sender_name' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.sender_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'sender_email' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.sender_email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'replyto_name' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.replyto_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'replyto_email' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.replyto_email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'inject_open_spy' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.inject_open_spy',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'inject_links_spy' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.inject_links_spy',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'bounce_account' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.bounce_account',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_newsletter_domain_model_bounceaccount',
                'items' => [['', 0]],
                'maxitems' => 1,
                'wizards' => [
                    'edit' => [
                        'type' => 'popup',
                        'icon' => $wizardIcon,
                        'module' => [
                            'name' => 'wizard_edit',
                        ],
                    ],
                ],
            ],
        ],
        'recipient_list' => [
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.recipient_list',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_newsletter_domain_model_recipientlist',
                'maxitems' => 1,
                'wizards' => [
                    'edit' => [
                        'type' => 'popup',
                        'icon' => $wizardIcon,
                        'module' => [
                            'name' => 'wizard_edit',
                        ],
                    ],
                ],
            ],
        ],
    ],
];

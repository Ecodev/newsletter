<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$TCA['tx_newsletter_domain_model_newsletter'] = array(
    'ctrl' => $TCA['tx_newsletter_domain_model_newsletter']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'planned_time,begin_time,end_time,repetition,plain_converter,is_test,attachments,sender_name,sender_email,inject_open_spy,inject_links_spy,bounce_account,recipient_list',
    ),
    'types' => array(
        '1' => array('showitem' => 'planned_time,begin_time,end_time,repetition,plain_converter,is_test,attachments,sender_name,sender_email,inject_open_spy,inject_links_spy,bounce_account,recipient_list'),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    ),
    'columns' => array(
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'planned_time' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.planned_time',
            'config' => array(
                'type' => 'input',
                'size' => 12,
                'eval' => 'datetime,required',
            ),
        ),
        'begin_time' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.begin_time',
            'config' => array(
                'type' => 'input',
                'size' => 12,
                'readOnly' => true,
                'eval' => 'datetime',
            ),
        ),
        'end_time' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.end_time',
            'config' => array(
                'type' => 'input',
                'size' => 12,
                'readOnly' => true,
                'eval' => 'datetime',
            ),
        ),
        'repetition' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition',
            'config' => array(
                'type' => 'select',
                'items' => array(
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition_none', '0'),
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition_daily', '1'),
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition_weekly', '2'),
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition_biweekly', '3'),
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition_monthly', '4'),
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition_quarterly', '5'),
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition_semiyearly', '6'),
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.repetition_yearly', '7'),
                ),
                'size' => 1,
                'maxitems' => 1,
            ),
        ),
        'plain_converter' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.plain_converter',
            'config' => array(
                'type' => 'select',
                'items' => array(
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.plain_converter_builtin', 'Ecodev\\Newsletter\\Domain\\Model\\PlainConverter\\Builtin'),
                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.plain_converter_lynx', 'Ecodev\\Newsletter\\Domain\\Model\\PlainConverter\\Lynx'),
                ),
                'size' => 1,
                'maxitems' => 1,
            ),
        ),
        'is_test' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.is_test',
            'config' => array(
                'type' => 'check',
                'default' => 0,
            ),
        ),
        'attachments' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.attachments',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'file',
                'allowed' => '',
                'disallowed' => 'php,php3',
                'max_size' => 500,
                'uploadfolder' => 'uploads/tx_newsletter',
                'size' => 3,
                'minitems' => 0,
                'maxitems' => 10,
            ),
        ),
        'sender_name' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.sender_name',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ),
        ),
        'sender_email' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.sender_email',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ),
        ),
        'inject_open_spy' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.inject_open_spy',
            'config' => array(
                'type' => 'check',
                'default' => 0,
            ),
        ),
        'inject_links_spy' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.inject_links_spy',
            'config' => array(
                'type' => 'check',
                'default' => 0,
            ),
        ),
        'bounce_account' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.bounce_account',
            'config' => array(
                'type' => 'select',
                'foreign_table' => 'tx_newsletter_domain_model_bounceaccount',
                'items' => array(array('', 0)),
                'maxitems' => 1,
                'wizards' => array(
                    'edit' => array(
                        'type' => 'popup',
                        'icon' => 'edit2.gif',
                        'script' => 'wizard_edit.php',
                    ),
                ),
            ),
        ),
        'recipient_list' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_newsletter.recipient_list',
            'config' => array(
                'type' => 'select',
                'foreign_table' => 'tx_newsletter_domain_model_recipientlist',
                'maxitems' => 1,
                'wizards' => array(
                    'edit' => array(
                        'type' => 'popup',
                        'icon' => 'edit2.gif',
                        'script' => 'wizard_edit.php',
                    ),
                ),
            ),
        ),
    ),
);

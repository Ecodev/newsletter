<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$TCA['tx_newsletter_domain_model_email'] = array(
    'ctrl' => $TCA['tx_newsletter_domain_model_email']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'begin_time,end_time,recipient_address,recipient_data,open_time,bounce_time,unsubscribed,newsletter',
    ),
    'types' => array(
        '1' => array('showitem' => 'begin_time,end_time,recipient_address,recipient_data,open_time,bounce_time,unsubscribed,newsletter'),
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
            )
        ),
        'begin_time' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_email.begin_time',
            'config' => array(
                'type' => 'input',
                'size' => 12,
                'readOnly' => true,
                'eval' => 'datetime',
            ),
        ),
        'end_time' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_email.end_time',
            'config' => array(
                'type' => 'input',
                'size' => 12,
                'readOnly' => true,
                'eval' => 'datetime',
            ),
        ),
        'recipient_address' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_email.recipient_address',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'readOnly' => true,
                'eval' => 'trim,required',
            ),
        ),
        'recipient_data' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_email.recipient_data',
            'config' => array(
                'type' => 'user',
                'userFunc' => 'Tx_Newsletter_Tca_EmailTca->render',
                'size' => 30,
                'eval' => 'trim',
            ),
        ),
        'open_time' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_email.open_time',
            'config' => array(
                'type' => 'check',
                'default' => 0,
                'readOnly' => true,
            ),
        ),
        'bounce_time' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_email.bounce_time',
            'config' => array(
                'type' => 'check',
                'default' => 0,
                'readOnly' => true,
            ),
        ),
        'newsletter' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_email.newsletter',
            'config' => array(
                'type' => 'inline',
                'foreign_table' => 'tx_newsletter_domain_model_newsletter',
                'minitems' => 0,
                'maxitems' => 1,
                'appearance' => array(
                    'collapse' => 0,
                    'levelLinksPosition' => 'bottom',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ),
            ),
        ),
        'unsubscribed' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xlf:tx_newsletter_domain_model_email.unsubscribed',
            'config' => array(
                'type' => 'check',
                'default' => 0,
                'readOnly' => true,
            ),
        ),
    ),
);



<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$TCA['tx_newsletter_domain_model_bounceaccount'] = array(
    'ctrl' => $TCA['tx_newsletter_domain_model_bounceaccount']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'email,server,protocol,username,password',
    ),
    'types' => array(
        '1' => array('showitem' => 'email,protocol,server,username,password'),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    ),
    'columns' => array(
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config' => array(
                'type' => 'check',
            )
        ),
        'email' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_bounceaccount.email',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ),
        ),
        'server' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_bounceaccount.server',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'protocol' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_bounceaccount.protocol',
            'config' => array(
                'type' => 'select',
                'items' => array(
                    array('POP3', 'pop3'),
                    array('IMAP', 'imap'),
                ),
                'size' => 1,
                'maxitems' => 1,
            ),
        ),
        'username' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_bounceaccount.username',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'password' => array(
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_bounceaccount.password',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'password'
            ),
        ),
    ),
);

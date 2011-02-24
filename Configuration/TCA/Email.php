<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_newsletter_domain_model_email'] = array(
	'ctrl' => $TCA['tx_newsletter_domain_model_email']['ctrl'],
	'interface' => array(
		'showRecordFieldList'	=> 'begin_time,end_time,recipient_address,recipient_data,opened,bounced,host,newsletter',
	),
	'types' => array(
		'1' => array('showitem'	=> 'begin_time,end_time,recipient_address,recipient_data,opened,bounced,host,newsletter'),
	),
	'palettes' => array(
		'1' => array('showitem'	=> ''),
	),
	'columns' => array(
		'hidden' => array(
			'exclude'	=> 1,
			'label'		=> 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'	=> array(
				'type'	=> 'check',
			)
		),
		'begin_time' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_email.begin_time',
			'config'	=> array(
				'type' => 'input',
				'size' => 12,
				'readOnly' => true,
				'eval' => 'datetime',
			),
		),
		'end_time' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_email.end_time',
			'config'	=> array(
				'type' => 'input',
				'size' => 12,
				'readOnly' => true,
				'eval' => 'datetime',
			),
		),
		'recipient_address' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_email.recipient_address',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'recipient_data' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_email.recipient_data',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'opened' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_email.opened',
			'config'	=> array(
				'type' => 'check',
				'default' => 0
			),
		),
		'bounced' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_email.bounced',
			'config'	=> array(
				'type' => 'check',
				'default' => 0
			),
		),
		'host' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_email.host',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'newsletter' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_email.newsletter',
			'config'	=> array(
				'type' => 'inline',
				'foreign_table' => 'tx_newsletter_domain_model_newsletter',
				'minitems' => 0,
				'maxitems' => 1,
				'appearance' => array(
					'collapse' => 0,
					'newRecordLinkPosition' => 'bottom',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1
				),
			),
		),
	),
);
?>
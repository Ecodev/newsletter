<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_newsletter_domain_model_link'] = array(
	'ctrl' => $TCA['tx_newsletter_domain_model_link']['ctrl'],
	'interface' => array(
		'showRecordFieldList'	=> 'url,opened_count,newsletter',
	),
	'types' => array(
		'1' => array('showitem'	=> 'url,opened_count,newsletter'),
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
		'url' => array(
			'exclude'	=> 1,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_link.url',
			'config'	=> array(
				'type' => 'input',
				'size' => 40,
				'eval' => 'trim'
			),
		),
		'opened_count' => array(
			'exclude'	=> 1,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_link.opened_count',
			'config'	=> array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int,'
			),
		),
		'newsletter' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_link.newsletter',
			'config'	=> array(
				'readOnly' => true,
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

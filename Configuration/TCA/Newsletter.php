<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_newsletter_domain_model_newsletter'] = array(
	'ctrl' => $TCA['tx_newsletter_domain_model_newsletter']['ctrl'],
	'interface' => array(
		'showRecordFieldList'	=> 'planned_time,begin_time,end_time,repetition,plain_converter,is_test,attachments,sender_name,sender_email,inject_open_spy,inject_links_spy,bounce_account,recipient_list',
	),
	'types' => array(
		'1' => array('showitem'	=> 'planned_time,begin_time,end_time,repetition,plain_converter,is_test,attachments,sender_name,sender_email,inject_open_spy,inject_links_spy,bounce_account,recipient_list'),
	),
	'palettes' => array(
		'1' => array('showitem'	=> ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude'			=> 1,
			'label'				=> 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config'			=> array(
				'type'					=> 'select',
				'foreign_table'			=> 'sys_language',
				'foreign_table_where'	=> 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.php:LGL.default_value', 0)
				),
			)
		),
		'l18n_parent' => array(
			'displayCond'	=> 'FIELD:sys_language_uid:>:0',
			'exclude'		=> 1,
			'label'			=> 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config'		=> array(
				'type'			=> 'select',
				'items'			=> array(
					array('', 0),
				),
				'foreign_table' => 'tx_newsletter_domain_model_newsletter',
				'foreign_table_where' => 'AND tx_newsletter_domain_model_newsletter.uid=###REC_FIELD_l18n_parent### AND tx_newsletter_domain_model_newsletter.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => array(
			'config'		=>array(
				'type'		=>'passthrough',
			)
		),
		't3ver_label' => array(
			'displayCond'	=> 'FIELD:t3ver_label:REQ:true',
			'label'			=> 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
			'config'		=> array(
				'type'		=>'none',
				'cols'		=> 27,
			)
		),
		'hidden' => array(
			'exclude'	=> 1,
			'label'		=> 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'	=> array(
				'type'	=> 'check',
			)
		),
		'planned_time' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_newsletter.planned_time',
			'config'	=> array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int,required'
			),
		),
		'begin_time' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_newsletter.begin_time',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'end_time' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_newsletter.end_time',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'repetition' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_newsletter.repetition',
			'config'	=> array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			),
		),
		'plain_converter' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_newsletter.plain_converter',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'is_test' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_newsletter.is_test',
			'config'	=> array(
				'type' => 'check',
				'default' => 0
			),
		),
		'attachments' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_newsletter.attachments',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'sender_name' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_newsletter.sender_name',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'sender_email' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_newsletter.sender_email',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'inject_open_spy' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_newsletter.inject_open_spy',
			'config'	=> array(
				'type' => 'check',
				'default' => 0
			),
		),
		'inject_links_spy' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_newsletter.inject_links_spy',
			'config'	=> array(
				'type' => 'check',
				'default' => 0
			),
		),
		'bounce_account' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_newsletter.bounce_account',
			'config'	=> array(
				'type' => 'inline',
				'foreign_table' => 'tx_newsletter_domain_model_bounceaccount',
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
		'recipient_list' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_newsletter.recipient_list',
			'config'	=> array(
				'type' => 'inline',
				'foreign_table' => 'tx_newsletter_domain_model_recipientlist',
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
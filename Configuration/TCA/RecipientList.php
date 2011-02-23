<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_newsletter_domain_model_recipientlist'] = array(
	'ctrl' => $TCA['tx_newsletter_domain_model_recipientlist']['ctrl'],
	'interface' => array(
		'showRecordFieldList'	=> 'title,plain_only,lang,type,be_users,fe_groups,fe_pages,tt_address,csv_url,csv_separator,csv_fields,csv_filename,csv_values,sql_statement,html_file,html_fetch_type,calculated_recipients,confirmed_recipients',
	),
	'types' => array(
		'1' => array('showitem'	=> 'title,plain_only,lang,type,be_users,fe_groups,fe_pages,tt_address,csv_url,csv_separator,csv_fields,csv_filename,csv_values,sql_statement,html_file,html_fetch_type,calculated_recipients,confirmed_recipients'),
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
				'foreign_table' => 'tx_newsletter_domain_model_recipientlist',
				'foreign_table_where' => 'AND tx_newsletter_domain_model_recipientlist.uid=###REC_FIELD_l18n_parent### AND tx_newsletter_domain_model_recipientlist.sys_language_uid IN (-1,0)',
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
		'title' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.title',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'plain_only' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.plain_only',
			'config'	=> array(
				'type' => 'check',
				'default' => 0
			),
		),
		'lang' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.lang',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'type' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'be_users' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.be_users',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'fe_groups' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.fe_groups',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'fe_pages' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.fe_pages',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'tt_address' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.tt_address',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'csv_url' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csv_url',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'csv_separator' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csv_separator',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'csv_fields' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csv_fields',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'csv_filename' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csv_filename',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'csv_values' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csv_values',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'sql_statement' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.sql_statement',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'html_file' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.html_file',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'html_fetch_type' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.html_fetch_type',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'calculated_recipients' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.calculated_recipients',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'confirmed_recipients' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.confirmed_recipients',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
	),
);
?>
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
				'type' => 'user',
				'userFunc' => 'tx_newsletter_email_show_data',
				'size' => 30,
				'eval' => 'trim',
				
			),
		),
		'opened' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_email.opened',
			'config'	=> array(
				'type' => 'check',
				'default' => 0,
				'readOnly' => true,
			),
		),
		'bounced' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_email.bounced',
			'config'	=> array(
				'type' => 'check',
				'default' => 0,
				'readOnly' => true,
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
					'levelLinksPosition' => 'bottom',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1
				),
			),
		),
	),
);

/**
 * Returns an HTML table showing recipient_data content
 * @param $PA
 * @param $fObj
 */
function tx_newsletter_email_show_data($PA, $fObj)
{
	$data = unserialize($PA['row']['recipient_data']);	
	$keys = array_keys($data);
	
	$result = '<table style="border: 1px grey solid; border-collapse: collapse;">';
	$result .= '<tr>';
	foreach ($keys as $key)
	{
		$result .= '<th style="padding-right: 1em;">' . $key . '</th>';
	}
	$result .= '</tr>';
	
	$result .= '<tr style="border: 1px grey solid; border-collapse: collapse;">';
	foreach ($data as $value)
	{
		$result .= '<td style="padding-right: 1em;">' . $value . '</td>';
	}
	$result .= '</tr>';
	$result .= '</table>';
	
	return $result;
}

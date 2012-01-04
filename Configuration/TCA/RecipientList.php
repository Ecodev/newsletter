<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_newsletter_domain_model_recipientlist'] = array (
    'ctrl' => $TCA['tx_newsletter_domain_model_recipientlist']['ctrl'],
    'interface' => array (
        'showRecordFieldList' => 'hidden,title'
    ),
    'feInterface' => $TCA['tx_newsletter_domain_model_recipientlist']['feInterface'],
    'columns' => array (
        'hidden' => array (        
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config' => array (
                'type' => 'check',
                'default' => '0'
            )
        ),
        'title' => array (        

            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.title',        
            'config' => array (
                'type' => 'input',    
                'size' => '30',
				'eval' => 'trim,required',
            )
        ),

        'plain_only' => array (
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.plain_only',
            'config' => array (
                'type' => 'check',
                'default' => '0'
            )
        ),
	
		'lang' => array (
		    'label' => 'LLL:EXT:lang/locallang_tca.php:sys_language',
		    'config' => array (
	                'type' => 'select',    
			'foreign_table' => 'sys_language',    
			'foreign_table_where' => 'ORDER BY sys_language.uid',    
			'size' => 1,    
			'minitems' => 0,
			'maxitems' => 1,	    
			'items' => array(
				'0' => array('',-1),
				'1' => array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
			),
		    ),
		),
		
		'be_users' => array (
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.be_users',
		    'config' => array (
	                'type' => 'select',    
			'foreign_table' => 'be_users',    
			'foreign_table_where' => 'ORDER BY be_users.uid',    
			'size' => 5,    
			'minitems' => 0,
			'maxitems' => 100,	    
		    ),
		),
		
		'fe_groups' => array (
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.fe_groups',
		    'config' => array (
	                'type' => 'group',    
			'internal_type' => 'db',    
			'allowed' => 'fe_groups',    
			'size' => 5,    
			'minitems' => 0,
			'maxitems' => 100,	    
		    ),
		),
		
		'fe_pages' => array (
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.fe_pages',
		    'config' => array (
	                'type' => 'group',    
			'internal_type' => 'db',    
			'allowed' => 'pages',    
			'size' => 5,    
			'minitems' => 0,
			'maxitems' => 100,	    
		    ),
		),
	
		
		'sql_statement' => array (
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.sql_statement',
		    'config' => array (
			'type' => 'text',
			'cols' => '50',
			'rows' => '10',
		    ),
		),
		
		'sql_register_bounce' => array (
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.sql_register_bounce',
		    'config' => array (
			'type' => 'text',
			'cols' => '50',
			'rows' => '10',
		    ),
		),
		
		'sql_register_open' => array (
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.sql_register_open',
		    'config' => array (
			'type' => 'text',
			'cols' => '50',
			'rows' => '10',
		    ),
		),
	
		'sql_register_click' => array (
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.sql_register_click',
		    'config' => array (
			'type' => 'text',
			'cols' => '50',
			'rows' => '10',
		    ),
		),
		
		'csv_separator' => array (
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csv_separator',
		    'config' => array(
			'type' => 'input',
			'size' => 1,
		    ),
		),
		
		'csv_fields' => array (
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csv_fields',
		    'config' => array(
			'type' => 'input',
			'size' => 20,
		    ),
		),
		
		'csv_values' => array (
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csv_values',
		    'config' => array(
			'type' => 'text',
			'cols' => 40,
			'rows' => 10,
		    ),	
		),
	
		'csv_filename' => array (
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csv_file',
		    'config' => array(
	                'type' => 'group',
			'internal_type' => 'file',
			'allowed' => 'csv,txt',
			'max_size' => 500,    
			'uploadfolder' => 'uploads/tx_newsletter',
			'size' => 1,    
			'minitems' => 0,
			'maxitems' => 1,
		    ),	
		),
	
		'csv_url' => array(
	            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csv_url',
	            'config' => array(
	                'type' => 'input',
	                'size' => 20,
	            ),
	
		),
	
	
		'type' => array (
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type',
		    'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type_be_users', 'Tx_Newsletter_Domain_Model_RecipientList_BeUsers'),
					array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type_fe_groups', 'Tx_Newsletter_Domain_Model_RecipientList_FeGroups'),
					array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type_fe_pages', 'Tx_Newsletter_Domain_Model_RecipientList_FePages'),
					array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type_sql', 'Tx_Newsletter_Domain_Model_RecipientList_Sql'),
					array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type_csv_file', 'Tx_Newsletter_Domain_Model_RecipientList_CsvFile'),
					array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type_csv_list', 'Tx_Newsletter_Domain_Model_RecipientList_CsvList'),
					array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type_csv_url', 'Tx_Newsletter_Domain_Model_RecipientList_CsvUrl'),
					array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type_html', 'Tx_Newsletter_Domain_Model_RecipientList_Html'), 
					),
				'size' => 1,
				'maxitems' => 1,
				'default' => 'Tx_Newsletter_Domain_Model_RecipientList_BeUsers',
		    ),
		),
	
		'html_url' => array(
	            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.html_url',
	            'config' => array(
	                'type' => 'input',
	                'size' => 20,
					'eval' => 'trim,required',
	            ),	
		),
	
		'html_fetch_type' => array (
	            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.html_fetch_type',
	            'config' => array(
	                'type' => 'select',
	                'items' => array(
	                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.html_fetch_type_mailto', 'mailto'),
	                    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.html_fetch_type_regex', 'regex'),
	                    ),
	                'size' => 1,
	                'maxitems' => 1,
	            ),
		),

	
        'recipients_preview' => array (
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang.xml:preview',
            'config' => array (
                'type' => 'user',
                'userFunc' => 'tx_newsletter_recipientlist_show_recipients',
            ),
        ),
    ),
    
    'types' => array (
        '0' => array('showitem' => 'hidden;;1;;1-1-1, title, type'),    
		'Tx_Newsletter_Domain_Model_RecipientList_BeUsers' => array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, lang, type, be_users, ;;;;2-2-2, recipients_preview'),
		'Tx_Newsletter_Domain_Model_RecipientList_FeGroups' => array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, lang, type, fe_groups,;;;;2-2-2, recipients_preview'),
		'Tx_Newsletter_Domain_Model_RecipientList_FePages' => array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, lang, type, fe_pages,;;;;2-2-2, recipients_preview'),
		'Tx_Newsletter_Domain_Model_RecipientList_Sql' => array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, type, sql_statement, sql_register_bounce, sql_register_open, sql_register_click,;;;;2-2-2, recipients_preview'),
		'Tx_Newsletter_Domain_Model_RecipientList_CsvFile' => array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, type, csv_separator, csv_fields, csv_filename,;;;;2-2-2, recipients_preview'),
		'Tx_Newsletter_Domain_Model_RecipientList_CsvList' => array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, type, csv_separator, csv_fields, csv_values,;;;;2-2-2, recipients_preview'),
		'Tx_Newsletter_Domain_Model_RecipientList_CsvUrl' => array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, type, csv_separator, csv_fields, csv_url,;;;;2-2-2, recipients_preview'),
		'Tx_Newsletter_Domain_Model_RecipientList_Html' => array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, lang, type, html_url, html_fetch_type,;;;;2-2-2, recipients_preview'),
    ),
    'palettes' => array (
         '1' => array('showitem' => ''),
    )
);


function tx_newsletter_recipientlist_show_recipients($PA, $fObj)
{
	$result = '';
	$uid = intval($PA['row']['uid']);
	if ($uid != 0)
	{
		$recipientListRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_RecipientListRepository');
		$recipientList = $recipientListRepository->findByUidInitialized($uid);
		
		$result .= $recipientList->getExtract();
	}
	
	return $result;
}

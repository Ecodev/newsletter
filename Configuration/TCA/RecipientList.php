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
            )
        ),

        'plain_only' => array (        
            'exclude' => 1,    
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.plain_only',
            'config' => array (
                'type' => 'check',
                'default' => '0'
            )
        ),
	
		'lang' => array (
		    'exclude' => 1,
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
		    'exclude' => 1,
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
		    'exclude' => 1,
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
		    'exclude' => 1,
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
		    'exclude' => 1,
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.sql_statement',
		    'config' => array (
			'type' => 'text',
			'cols' => '50',
			'rows' => '10',
		    ),
		),
		
		'sql_statement' => array (
		    'exclude' => 1,
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.sql_statement',
		    'config' => array (
			'type' => 'text',
			'cols' => '50',
			'rows' => '10',
		    ),
		),
		
		'csv_separator' => array (
		    'exclude' => 1,
			'label'		=> 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csv_separator',
		    'config' => array(
			'type' => 'input',
			'size' => 1,
		    ),
		),
		
		'csv_fields' => array (
		    'exclude' => 1,
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csv_fields',
		    'config' => array(
			'type' => 'input',
			'size' => 20,
		    ),
		),
		
		'csv_values' => array (
		    'exclude' => 1,
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csv_data',
		    'config' => array(
			'type' => 'text',
			'cols' => 40,
			'rows' => 10,
		    ),	
		),
	
		'csv_filename' => array (
		    'exclude' => 1,
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csv_file',
		    'config' => array(
	                'type' => 'group',
			'internal_type' => 'file',
			'allowed' => 'txt,csv_',    
			'max_size' => 500,    
			'uploadfolder' => 'uploads/tx_newsletter',
			'size' => 1,    
			'minitems' => 0,
			'maxitems' => 1,
		    ),	
		),
	
	
		'csv_url' => array(
	            'exclude' => 1,
	            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csv_url',
	            'config' => array(
	                'type' => 'input',
	                'size' => 20,
	            ),
	
		),
	
	
		'type' => array (
		    'exclude' => 1,
		    'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type',
		    'config' => array(
			'type' => 'select',
			'items' => array(
			    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type_be_users', 'Tx_Newsletter_Domain_Model_RecipientList_BeUsers'),
			    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type_fe_groups', 'Tx_Newsletter_Domain_Model_RecipientList_FeGroups'),
			    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type_fe_pages', 'Tx_Newsletter_Domain_Model_RecipientList_FePages'),
			    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type_raw_sql', 'Tx_Newsletter_Domain_Model_RecipientList_RawSql'),
			    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type_csv_file', 'Tx_Newsletter_Domain_Model_RecipientList_CsvFile'),
			    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type_csv_list', 'Tx_Newsletter_Domain_Model_RecipientList_CsvList'),
			    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type_csv_url', 'Tx_Newsletter_Domain_Model_RecipientList_CsvUrl'),
			    array('LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type_html', 'Tx_Newsletter_Domain_Model_RecipientList_Html'), 
			    ),
			'size' => 1,
			'maxitems' => 1,
		    ),
		),
	
		'html_file' => array(
	            'exclude' => 1,
	            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.htmlurl',
	            'config' => array(
	                'type' => 'input',
	                'size' => 20,
	            ),
	
		),
	
		'html_fetch_type' => array (
	            'exclude' => 1,
	            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.htmlfetch',
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

	
        'calculated_recipients' => array (
            'label' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist.actual_receivers',
            'config' => array (
                'type' => 'user',
                'userFunc' => 'tx_newsletter_recipientlist_show_recipients',
            ),
        ),
    ),
    
    'types' => array (
        '0' => array('showitem' => 'hidden;;1;;1-1-1, title, type'),    
		'Tx_Newsletter_Domain_Model_RecipientList_BeUsers' => array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, lang, type, be_users, ;;;;2-2-2, calculated_recipients'),
		'Tx_Newsletter_Domain_Model_RecipientList_FeGroups' => array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, lang, type, fe_groups,;;;;2-2-2, calculated_recipients'),
		'Tx_Newsletter_Domain_Model_RecipientList_FePages' => array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, lang, type, fe_pages,;;;;2-2-2, calculated_recipients'),
		'Tx_Newsletter_Domain_Model_RecipientList_RawSql' => array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, type, sql_statement,;;;;2-2-2, calculated_recipients'),
		'Tx_Newsletter_Domain_Model_RecipientList_CsvFile' => array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, type, csv_separator, csv_fields, csv_filename,;;;;2-2-2, calculated_recipients'),
		'Tx_Newsletter_Domain_Model_RecipientList_CsvList' => array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, type, csv_separator, csv_fields, csv_values,;;;;2-2-2, calculated_recipients'),
		'Tx_Newsletter_Domain_Model_RecipientList_CsvUrl' => array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, type, csv_separator, csv_fields, csv_url,;;;;2-2-2, calculated_recipients'),
		'Tx_Newsletter_Domain_Model_RecipientList_Html' => array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, lang, type, html_file, html_fetch_type,;;;;2-2-2, calculated_recipients'),
    ),
    'palettes' => array (
         '1' => array('showitem' => ''),
    )
);


function tx_newsletter_recipientlist_show_recipients($PA, $fObj)
{
	$result = '<strong>Preview</strong>';
	$uid = intval($PA['row']['uid']);
	if ($uid != 0)
	{
		$target = Tx_Newsletter_Domain_Model_RecipientList::loadTarget($uid);
		$result .= $target->getExtract();
	}
	
	return $result;
}

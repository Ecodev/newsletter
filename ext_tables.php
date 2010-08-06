<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");

$tempColumns = Array (
	"tx_newsletter_senttime" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_senttime",		
		"config" => Array (
			"type" => "input",
			"size" => "12",
			"max" => "20",
			"eval" => "datetime",
			"checkbox" => "0",
			"default" => "0"
		)
	),
	"tx_newsletter_repeat" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_repeat",		
		"config" => Array (
			"type" => "select",
			"items" => Array (
				Array("LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_repeat.I.0", "0"),
				Array("LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_repeat.I.1", "1"),
				Array("LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_repeat.I.2", "2"),
				Array("LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_repeat.I.3", "3"),
				Array("LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_repeat.I.4", "4"),
				Array("LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_repeat.I.5", "5"),
				Array("LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_repeat.I.6", "6"),
				Array("LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_repeat.I.7", "7"),
			),
			"size" => 1,	
			"maxitems" => 1,
		)
	),
	
	"tx_newsletter_plainconvert" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_plainconvert",		
		"config" => Array (
			"type" => "select",
			"items" => Array (
				Array("LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_plainconvert.I.2", "tx_newsletter_plain_simple"),
				Array("LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_plainconvert.I.0", "tx_newsletter_plain_template"),
				Array("LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_plainconvert.I.1", "tx_newsletter_plain_lynx"),
				Array("LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_plainconvert.I.3", "tx_newsletter_plain_html2text"),
			),
			"size" => 1,	
			"maxitems" => 1,
		)
	),
	
        "tx_newsletter_attachfiles" => Array (        
                "exclude" => 1,        
	        "label" => "LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_attachfiles",        
        	"config" => Array (
	        	"type" => "group",
        		"internal_type" => "file",
            		"allowed" => "",    
            		"disallowed" => "php,php3",    
            		"max_size" => 500,    
            		"uploadfolder" => "uploads/tx_newsletter",
           		"size" => 3,    
            		"minitems" => 0,
            		"maxitems" => 10,
        	)
    	),
    "tx_newsletter_real_target" => Array (        
        "exclude" => 1,        
        "label" => "LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_real_target",        
        "config" => Array (
            "type" => "group",    
            "internal_type" => "db",    
            "allowed" => "tx_newsletter_targets",    
            "size" => 5,    
            "minitems" => 0,
            "maxitems" => 20,
        )
    ),
    "tx_newsletter_test_target" => Array (        
        "exclude" => 1,        
        "label" => "LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_test_target",        
        "config" => Array (
            "type" => "group",    
            "internal_type" => "db",    
            "allowed" => "tx_newsletter_targets",    
            "size" => 1,    
            "minitems" => 0,
            "maxitems" => 1,
        )
    ),
    'tx_newsletter_sendername' => Array (
	'exclude' => 1,
	'label' => 'LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_sendername',
	'config' => Array (
	    'type' => 'input',
	    'size' => 30,
	)
    ),
    
    'tx_newsletter_senderemail' => Array (
	'exclude' => 1,
	'label' => 'LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_senderemail',
	'config' => Array (
	    'type' => 'input',
	    'size' => 30,
	)
    ),    

    'tx_newsletter_bounceaccount' => Array (
       'exclude' => 1,
       'label' => 'LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_bounceaccount',
       'config' => Array (
           "type" => "select",
           "foreign_table" => "tx_newsletter_bounceaccount",
           "foreign_table_where" => "ORDER BY tx_newsletter_bounceaccount.uid",
           "size" => 1,
           "minitems" => 0,
           "maxitems" => 1,
        ),
    ),


    
    'tx_newsletter_spy' => Array (
	'exclude' => 1,
	'label' => 'LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_spy',
	'config' => Array(
	    'type' => 'check',
	),
    ),
    
    'tx_newsletter_register_clicks' => Array (
	'exclude' => 1,
	'label' => 'LLL:EXT:newsletter/locallang_db.xml:pages.tx_newsletter_register_clicks',
	'config' => Array(
	    'type' => 'check',
	),
    ),              
);


t3lib_div::loadTCA("pages");
t3lib_extMgm::addTCAcolumns("pages",$tempColumns,1);


global $PAGES_TYPES;
$PAGES_TYPES[189] = Array(
    "type" => "Directmail",
    "icon" => t3lib_extMgm::extRelPath('newsletter')."mail.gif",
);
      

array_splice ($TCA["pages"]["columns"]["doktype"]["config"]["items"], 3, 0, array(array(  
	0 => "LLL:EXT:newsletter/locallang_db.xml:pages.directmailtype",
	1 => 189,
	2 => t3lib_extMgm::extRelPath('newsletter')."mail.gif"
    ))
);

$TCA['pages']['types']['189'] = array (
    'showitem' => "hidden;;;;1-1-1, doktype, title;;;;2-2-2, storage_pid, content_from_pid,;;;;3-3-3, tx_newsletter_sendername, tx_newsletter_senderemail, tx_newsletter_bounceaccount, tx_newsletter_plainconvert, tx_newsletter_spy, tx_newsletter_register_clicks, tx_newsletter_usebcc,;;;;4-4-4, tx_newsletter_senttime, tx_newsletter_repeat, tx_newsletter_real_target,tx_newsletter_test_target,;;;;6-6-6, tx_newsletter_attachfiles,",
);

t3lib_extMgm::allowTableOnStandardPages("tx_newsletter_targets");
$TCA["tx_newsletter_targets"] = Array (
    "ctrl" => Array (
        "title" => "LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_targets",        
        "label" => "title",    
        "tstamp" => "tstamp",
        "crdate" => "crdate",
        "cruser_id" => "cruser_id",
        "default_sortby" => "ORDER BY crdate",    
        "delete" => "deleted",    
        "type" => "targettype",
        "enablecolumns" => Array (        
            "disabled" => "hidden",
        ),
        "dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
        "iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."mailtargets.gif",
    ),
    "feInterface" => Array (
        "fe_admin_fieldList" => "hidden, title",
    )
);

$TCA["tx_newsletter_bounceaccount"] = Array (
    "ctrl" => Array (
        "title" => "LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_bounceaccount",        
        "label" => "email",    
        "tstamp" => "tstamp",
        "crdate" => "crdate",
        "cruser_id" => "cruser_id",
        "default_sortby" => "ORDER BY crdate",    
        "delete" => "deleted",    
        "enablecolumns" => Array (        
            "disabled" => "hidden",
        ),
        "dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
        "iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."bounceaccount.gif",
    ),
    "feInterface" => Array (
        "fe_admin_fieldList" => "hidden, email",
    )
);


$tempColumns = Array (
    "tx_newsletter_bounce" => Array (        
        "exclude" => 1,        
        "label" => "LLL:EXT:newsletter/locallang_db.xml:fe_users.tx_newsletter_bounce",        
        "config" => Array (
            "type" => "input",
            "size" => "4",
            "max" => "4",
            "eval" => "int",
            "checkbox" => "0",
            "range" => Array (
                "upper" => "100",
                "lower" => "0"
            ),
            "default" => 0
        )
    ),
);

# Adds configuration
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/', 'Newsletter configuration');

// Loads BE modules
if (TYPO3_MODE=="BE")	{
	// temporary line
	t3lib_extMgm::addModule("web","newsletterM2","before:info",t3lib_extMgm::extPath($_EXTKEY)."mod2/");

	Tx_Extbase_Utility_Extension::registerModule(
		$_EXTKEY,
		'web',// Make newsletter module a submodule of 'user'
		'tx_newsletter_m1',  // Submodule key
		'before:info',           // Position
		array(
			'Newsletter' => 'index',
			'Statistic' => 'index',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:newsletter/Resources/Public/images/icons/ext_icon.png',
			'labels' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_module.xml',
		)
	);
}

//t3lib_div::loadTCA("fe_users");
//t3lib_extMgm::addTCAcolumns("fe_users",$tempColumns,1);
//t3lib_extMgm::addToAllTCAtypes("fe_users","tx_newsletter_bounce;;;;1-1-1");
//
//t3lib_div::loadTCA("tt_address");
//t3lib_extMgm::addTCAcolumns("tt_address",$tempColumns,1);
//t3lib_extMgm::addToAllTCAtypes("tt_address","tx_newsletter_bounce;;;;1-1-1");
//
//t3lib_div::loadTCA("be_users");
//t3lib_extMgm::addTCAcolumns("be_users",$tempColumns,1);
//t3lib_extMgm::addToAllTCAtypes("be_users","tx_newsletter_bounce;;;;1-1-1");
//


?>

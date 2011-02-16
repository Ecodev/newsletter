<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");


$TCA['tx_newsletter_domain_model_bounceaccount'] = array(
   "ctrl" => $TCA["tx_newsletter_domain_model_bounceaccount"]["ctrl"],
   "interface" => array(
      "showRecordFieldList" => 'hidden,email,servertype',
   ),
   'feInterface' => $TCA['tx_newsletter_domain_model_bounceaccount'],
   'columns' => array (
   
      'hidden' => array (        
         "exclude" => 1,    
         "label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
         "config" => Array (
            "type" => "check",
            "default" => "0"
         )
      ),
      
      'email' => array (
         'exclude' => 1,
         'label' => 'LLL:EXT:lang/locallang_general.php:LGL.email',
         'config' => array(
            'type' => 'input',
            'size' => 30,
         ), 
      ),
      
      'servertype' => array (
         'exclude' => 1,
         'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_bounceaccount.servertype',
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

      'server' => array (
         'exclude' => 1,
         'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_bounceaccount.server',
         'config' => array(
            'type' => 'input',
            'size' => 30,
         ), 
      ),   
      
      'username' => array (
         'exclude' => 1,
         'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_bounceaccount.username',
         'config' => array(
            'type' => 'input',
            'size' => 30,
         ), 
      ),      
      
      'passwd' => array (
         'exclude' => 1,
         'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_bounceaccount.passwd',
         'config' => array(
            'type' => 'input',
            'size' => 30,
         ), 
      ),        
           
   ),
   'types' => array(
      array('showitem' => 'hidden;;1;;1-1-1, title, email, servertype, server, username, passwd'),
   ),
);


$TCA["tx_newsletter_domain_model_recipientlist"] = Array (
    "ctrl" => $TCA["tx_newsletter_domain_model_recipientlist"]["ctrl"],
    "interface" => Array (
        "showRecordFieldList" => "hidden,title"
    ),
    "feInterface" => $TCA["tx_newsletter_domain_model_recipientlist"]["feInterface"],
    "columns" => Array (
        "hidden" => Array (        
            "exclude" => 1,    
            "label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
            "config" => Array (
                "type" => "check",
                "default" => "0"
            )
        ),
        "title" => Array (        

            "label" => "LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.title",        
            "config" => Array (
                "type" => "input",    
                "size" => "30",
            )
        ),

        "plain_only" => Array (        
            "exclude" => 1,    
            "label" => "LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.plain_only",
            "config" => Array (
                "type" => "check",
                "default" => "0"
            )
        ),

	'lang' => Array (
	    'exclude' => 1,
	    'label' => 'LLL:EXT:lang/locallang_tca.php:sys_language',
	    'config' => Array (
                "type" => "select",    
		"foreign_table" => "sys_language",    
		"foreign_table_where" => "ORDER BY sys_language.uid",    
		"size" => 1,    
		"minitems" => 0,
		"maxitems" => 1,	    
		"items" => array(
			'0' => array('',-1),
			'1' => array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
		),
	    ),
	),
	
	'beusers' => Array (
	    'exclude' => 1,
	    'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.beusers',
	    'config' => Array (
                "type" => "select",    
		"foreign_table" => "be_users",    
		"foreign_table_where" => "ORDER BY be_users.uid",    
		"size" => 5,    
		"minitems" => 0,
		"maxitems" => 100,	    
	    ),
	),
	
	'fegroups' => Array (
	    'exclude' => 1,
	    'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.fegroups',
	    'config' => Array (
                "type" => "group",    
		"internal_type" => "db",    
		"allowed" => "fe_groups",    
		"size" => 5,    
		"minitems" => 0,
		"maxitems" => 100,	    
	    ),
	),
	
	'fepages' => Array (
	    'exclude' => 1,
	    'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.fepages',
	    'config' => Array (
                "type" => "group",    
		"internal_type" => "db",    
		"allowed" => "pages",    
		"size" => 5,    
		"minitems" => 0,
		"maxitems" => 100,	    
	    ),
	),
	

	'ttaddress' => Array (
	    'exclude' => 1,
	    'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.ttaddress',
	    'config' => Array (
                "type" => "group",    
		"internal_type" => "db",    
		"allowed" => "pages",    
		"size" => 5,    
		"minitems" => 0,
		"maxitems" => 100,	    
	    ),
	),

	
	'rawsql' => Array (
	    'exclude' => 1,
	    'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.rawsql',
	    'config' => Array (
		'type' => 'text',
		'cols' => '50',
		'rows' => '10',
	    ),
	),
	
	'csvseparator' => Array (
	    'exclude' => 1,
	    'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.sepchar',
	    'config' => Array(
		'type' => 'input',
		'size' => 1,
	    ),
	),
	
	'csvfields' => Array (
	    'exclude' => 1,
	    'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csvfields',
	    'config' => Array(
		'type' => 'input',
		'size' => 20,
	    ),
	),
	
	'csvvalues' => Array (
	    'exclude' => 1,
	    'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csvdata',
	    'config' => Array(
		'type' => 'text',
		'cols' => 40,
		'rows' => 10,
	    ),	
	),

	'csvfilename' => Array (
	    'exclude' => 1,
	    'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csvfile',
	    'config' => Array(
                "type" => "group",
		"internal_type" => "file",
		"allowed" => "txt,csv",    
		"max_size" => 500,    
		"uploadfolder" => "uploads/tx_newsletter",
		"size" => 1,    
		"minitems" => 0,
		"maxitems" => 1,
	    ),	
	),


	'csvurl' => Array(
            'exclude' => 1,
            'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.csvurl',
            'config' => Array(
                'type' => 'input',
                'size' => 20,
            ),

	),


	"targettype" => Array (
	    "exclude" => 1,
	    'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.type',
	    "config" => array(
		'type' => 'select',
		'items' => array(
		    array('LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.optbeusers', 'tx_newsletter_target_beusers'),
		    array('LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.optfegroups', 'tx_newsletter_target_fegroups'),
		    array('LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.optfepages', 'tx_newsletter_target_fepages'),
		    array('LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.optttaddress', 'tx_newsletter_target_ttaddress'),
		    array('LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.optrawsql', 'tx_newsletter_target_rawsql'),
		    array('LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.optcsvfile', 'tx_newsletter_target_csvfile'),
		    array('LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.optcsvlist', 'tx_newsletter_target_csvlist'),
		    array('LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.optcsvurl', 'tx_newsletter_target_csvurl'),
		    array('LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.opthtml', 'tx_newsletter_target_html'), 
		    ),
		'size' => 1,
		'maxitems' => 1,
	    ),
	),

	'htmlfile' => Array(
            'exclude' => 1,
            'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.htmlurl',
            'config' => Array(
                'type' => 'input',
                'size' => 20,
            ),

	),

	'htmlfetchtype' => Array (
            "exclude" => 1,
            'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.htmlfetch',
            "config" => array(
                'type' => 'select',
                'items' => array(
                    array('LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.optmailto', 'mailto'),
                    array('LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.optregex', 'regex'),
                    ),
                'size' => 1,
                'maxitems' => 1,
            ),
	),

	
        "calculated_receivers" => array (
            'label' => 'LLL:EXT:newsletter/locallang_db.xml:tx_newsletter_domain_model_recipientlist.actual_receivers',
            'config' => array (
                'type' => 'user',
                'userFunc' => 'user_showreceivers',
            ),
        ),
    ),
    "types" => Array (
         "0" => Array("showitem" => "hidden;;1;;1-1-1, title, targettype"),
         'tx_newsletter_target_beusers' => Array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, lang, targettype, beusers, ;;;;2-2-2, calculated_receivers'),
         'tx_newsletter_target_fegroups' => Array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, lang, targettype, fegroups,;;;;2-2-2, calculated_receivers'),
         'tx_newsletter_target_fepages' => Array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, lang, targettype, fepages,;;;;2-2-2, calculated_receivers'),
         'tx_newsletter_target_ttaddress' => Array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, lang, targettype, ttaddress,;;;;2-2-2, calculated_receivers'),
         'tx_newsletter_target_rawsql' => Array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, targettype, rawsql,;;;;2-2-2, calculated_receivers'),
         'tx_newsletter_target_csvfile' => Array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, targettype, csvseparator, csvfields, csvfilename,;;;;2-2-2, calculated_receivers'),
         'tx_newsletter_target_csvlist' => Array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, targettype, csvseparator, csvfields, csvvalues,;;;;2-2-2, calculated_receivers'),
         'tx_newsletter_target_csvurl' => Array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, targettype, csvseparator, csvfields, csvurl,;;;;2-2-2, calculated_receivers'),
         'tx_newsletter_target_html' => Array('showitem' => 'hidden;;1;;1-1-1, title, plain_only, lang, targettype, htmlfile, htmlfetchtype,;;;;2-2-2, calculated_receivers'),
    ),
    "palettes" => Array (
         "1" => Array("showitem" => ""),
    )
);


if (!function_exists('user_displayfieldtitle')) {
	function user_displayfieldtitle ($fieldname) {
		switch ($fieldname) {
			case 'email':
			case 'plain_only':
			case 'authCode':
			case 'uid':
			case 'tableName':
			case 'L':
				return '<strong style="color: green;">'.$fieldname.'</strong>';

			default:
				if (preg_match ('/_[0-9]+$/', $fieldname)) {
					return '<strong style="color: red;">'.$fieldname.'</strong>';
				} else {
					return "<strong>$fieldname</strong>";
				}
		}
	}
}

if (!function_exists('user_showreceivers')) {
    function user_showreceivers($PA, $fObj) {
	require_once(t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_tools.php');
	global $TYPO3_DB;
    
	if (intval($PA['row']['uid']) == 0) {
	    return "";
	}
	
	$uid = $PA['row']['uid'];
	
    
	$target = tx_newsletter_target::loadTarget($uid);
    

	if ($target->getError()) {
	    return "Error";
	}
    
	$i = 0;
	$rows = array();
	while ($row = $target->getRecord()) {
	    if ($i == 0) {
		$rows[-1] = array_map ('user_displayfieldtitle', array_keys($row));
	    }
	
	    $rows[] = $row;    	
	
	    if ($i == 30) {
		$rows[] = array('<strong>...</strong>', '<strong>'.$target->getCount().'&nbsp;Total</strong>');
		break;
	    }
	    $i++;	
	}

	if ($i < 30) {
    	    $rows[] = array('<strong>'.$target->getCount().'&nbsp;Total</strong>');
	} 
    
	foreach ($rows as $row) {
	    $out .= '<tr>';
    	    foreach ($row as $field) {
		$out .= "<td>$field</td>";
	    }
    	    $out .= '</tr>';
	}
	
	$authCode = t3lib_div::stdAuthCode($target->fields);
	
    
	return '<div style="height: 240px; width:430px; overflow: scroll; background-color: white;">'
	      .'<p>Download: <a href="'.t3lib_extMgm::extRelPath('newsletter')."web/xmldownload.php?authCode=$authCode&uid=$uid\">XML</a>&nbsp;"
	      .'<a href="'.t3lib_extMgm::extRelPath('newsletter')."web/csvdownload.php?authCode=$authCode&uid=$uid\">CSV</a></p>"
	      .'<table>'.$out.'</table></div>';
    }
}

if (!function_exists('user_showalreadymailed')) {
   function user_showalreadymailed($PA, $fObj) {
      require_once(t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_tools.php');
      $target = tx_newsletter_target::getTarget($PA['row']['uid']);
      list($description, $sql) = unserialize($this->fields['alreadymailed']);
      return '<div style="height: 120px; width:430px; overflow: scroll; background-color: white;"><p>'.$description.'</p></div>';
   }
}

?>

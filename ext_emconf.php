<?php

########################################################################
# Extension Manager/Repository config file for ext: "newsletter"
#
# Auto generated 13-01-2007 00:15
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Directmail',
	'description' => 'Directmail extension with simple to setup and use mailer',
	'category' => 'module',
	'shy' => '',
	'version' => '2.0.3',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'mod2,cli,web',
	'state' => 'stable',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author' => 'Adrien Crivelli, Fabien Udriot, Daniel Schledermann',
	'author_email' => 'adrien.crivelli@ecodev.ch, fabien.udriot@ecodev.ch, info@newsletter.dk',
	'author_company' => 'Ecodev, Casalogic A/S',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'php' => '5.0.0-',
			'typo3' => '4.0.0-',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:55:{s:20:"advanced_connect.php";s:4:"7abf";s:13:"beenthere.php";s:4:"2019";s:10:"bounce.php";s:4:"99cf";s:17:"bounceaccount.gif";s:4:"4ef5";s:39:"class.tx_newsletter_bouncehandler.php";s:4:"d246";s:32:"class.tx_newsletter_mailer.php";s:4:"94bc";s:31:"class.tx_newsletter_plain.php";s:4:"9407";s:41:"class.tx_newsletter_plain_html2text.php";s:4:"2bfe";s:36:"class.tx_newsletter_plain_lynx.php";s:4:"21a2";s:38:"class.tx_newsletter_plain_simple.php";s:4:"05e9";s:40:"class.tx_newsletter_plain_template.php";s:4:"6856";s:32:"class.tx_newsletter_target.php";s:4:"c2e3";s:46:"class.tx_newsletter_target_alreadymailed.php";s:4:"5c0e";s:38:"class.tx_newsletter_target_array.php";s:4:"5c04";s:40:"class.tx_newsletter_target_beusers.php";s:4:"2b3e";s:40:"class.tx_newsletter_target_csvfile.php";s:4:"5215";s:40:"class.tx_newsletter_target_csvlist.php";s:4:"4be4";s:39:"class.tx_newsletter_target_csvurl.php";s:4:"60ef";s:41:"class.tx_newsletter_target_fegroups.php";s:4:"1329";s:40:"class.tx_newsletter_target_fepages.php";s:4:"e8f5";s:37:"class.tx_newsletter_target_html.php";s:4:"21e8";s:39:"class.tx_newsletter_target_rawsql.php";s:4:"a310";s:36:"class.tx_newsletter_target_sql.php";s:4:"fae8";s:42:"class.tx_newsletter_target_ttaddress.php";s:4:"734d";s:31:"class.tx_newsletter_tools.php";s:4:"c6c5";s:9:"click.php";s:4:"7276";s:21:"ext_conf_template.txt";s:4:"3c06";s:12:"ext_icon.gif";s:4:"593f";s:17:"ext_localconf.php";s:4:"50df";s:14:"ext_tables.php";s:4:"be61";s:14:"ext_tables.sql";s:4:"f165";s:16:"locallang_db.xml";s:4:"33b9";s:8:"mail.gif";s:4:"593f";s:10:"mailer.php";s:4:"d5fc";s:15:"mailtargets.gif";s:4:"d59a";s:11:"preview.php";s:4:"4e91";s:12:"readmail.php";s:4:"e156";s:18:"simple_connect.php";s:4:"3d37";s:7:"tca.php";s:4:"2097";s:10:"tclick.php";s:4:"71e5";s:14:"doc/manual.sxw";s:4:"994f";s:14:"mod2/clear.gif";s:4:"cc11";s:13:"mod2/conf.php";s:4:"04ce";s:14:"mod2/index.php";s:4:"4923";s:18:"mod2/locallang.xml";s:4:"0212";s:22:"mod2/locallang_mod.xml";s:4:"cdc9";s:19:"mod2/moduleicon.gif";s:4:"af7d";s:14:"mod2/clear.gif";s:4:"cc11";s:13:"mod2/conf.php";s:4:"a678";s:14:"mod2/index.php";s:4:"e492";s:18:"mod2/locallang.xml";s:4:"59cc";s:22:"mod2/locallang_mod.xml";s:4:"3892";s:19:"mod2/moduleicon.gif";s:4:"a5a1";s:57:"sections/class.tx_newsletter_section_modulefunction.php";s:4:"e62f";s:50:"sections/class.tx_newsletter_section_targets.php";s:4:"518c";}',
	'suggests' => array(
	),
);

?>

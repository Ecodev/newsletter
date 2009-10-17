<?php

########################################################################
# Extension Manager/Repository config file for ext: "mvc_extjs"
#
# Auto generated 17-10-2009 14:48
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'MVC + ExtJS',
	'description' => 'Base and Helper classes to efficiently use Extbase and Fluid (MVC) combined with ExtJS',
	'category' => 'misc',
	'author' => 'Xavier Perseguers',
	'author_email' => 'typo3@perseguers.ch',
	'shy' => '',
	'dependencies' => 'extbase,fluid',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.1.1',
	'constraints' => array(
		'depends' => array(
			'php' => '5.2.0-0.0.0',
			'typo3' => '4.3.0-0.0.0',
			'extbase' => '0.0.0-0.0.0',
			'fluid' => '0.0.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'sms_firephp' => '0.0.0-0.0.0',
		),
	),
	'_md5_values_when_last_written' => 'a:17:{s:9:"ChangeLog";s:4:"a448";s:10:"README.txt";s:4:"1a6e";s:16:"ext_autoload.php";s:4:"26dd";s:12:"ext_icon.gif";s:4:"0c18";s:24:"ext_typoscript_setup.txt";s:4:"155d";s:14:"doc/manual.sxw";s:4:"b8c2";s:23:"Classes/ExtJS/Array.php";s:4:"caaf";s:29:"Classes/ExtJS/FormElement.php";s:4:"37ab";s:24:"Classes/ExtJS/Object.php";s:4:"3781";s:33:"Classes/ExtJS/SettingsService.php";s:4:"af8c";s:25:"Classes/ExtJS/Utility.php";s:4:"44c7";s:32:"Classes/ExtJS/Layout/Toolbar.php";s:4:"5629";s:45:"Classes/ExtJS/Controller/ActionController.php";s:4:"d389";s:38:"Classes/ViewHelpers/JsonViewHelper.php";s:4:"543a";s:38:"Classes/ViewHelpers/NullViewHelper.php";s:4:"3485";s:33:"Configuration/TypoScript/ajax.txt";s:4:"7a35";s:39:"Resources/Private/Templates/module.html";s:4:"9bed";}',
	'suggests' => array(
	),
);

?>
<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "newsletter".
 *
 * Auto generated 25-01-2014 11:28
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Newsletter',
	'description' => 'Send any pages as Newsletter and provide statistics on opened emails and clicked links.',
	'category' => 'module',
	'shy' => 0,
	'version' => '2.1.1',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'cli,web',
	'state' => 'stable',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Ecodev',
	'author_email' => 'contact@ecodev.ch',
	'author_company' => 'Ecodev',
	'CGLcompliance' => NULL,
	'CGLcompliance_note' => NULL,
	'constraints' => 
	array (
		'depends' => 
		array (
			'cms' => '',
			'extbase' => '',
			'fluid' => '',
			'php' => '5.3.7-0.0.0',
			'typo3' => '6.0.0-6.1.99',
			'scheduler' => '1.1.0',
		),
		'conflicts' => '',
		'suggests' => 
		array (
		),
	),
);

?>
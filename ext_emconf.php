<?php

########################################################################
# Extension Manager/Repository config file for ext "newsletter".
#
# Auto generated 03-05-2012 09:14
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Newsletter',
	'description' => 'Send any pages as Newsletter and provide statistics on opened emails and clicked links.',
	'category' => 'module',
	'author' => 'Ecodev',
	'author_email' => 'contact@ecodev.ch',
	'author_company' => 'Ecodev',
	'shy' => '',
	'conflicts' => '',
	'priority' => '',
	'module' => 'cli,web',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => '',
	'lockType' => '',
	'version' => '1.2.3',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'extbase' => '',
			'fluid' => '',
			'php' => '5.2.9-0.0.0',
			'typo3' => '4.5.0-0.0.0',
			'mvc_extjs' => '0.2.0',
			'scheduler' => '1.1.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);

?>
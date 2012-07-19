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
	'description' => 'Newsletter extension with simple to setup and use mailer',
	'category' => 'module',
	'author' => 'Adrien Crivelli, Fabien Udriot, Daniel Schledermann',
	'author_email' => 'adrien.crivelli@ecodev.ch, fabien.udriot@ecodev.ch, info@newsletter.dk',
	'author_company' => 'Ecodev, Casalogic A/S',
	'shy' => '',
	'conflicts' => '',
	'priority' => '',
	'module' => 'cli,web',
	'state' => 'beta',
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
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);

?>
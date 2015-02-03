<?php

/*********************************************************************
* Extension configuration file for ext "newsletter".
*
* Generated by ext 03-02-2015 08:58 UTC
*
* https://github.com/t3elmar/Ext
*********************************************************************/

$EM_CONF[$_EXTKEY] = array (
  'title' => 'Newsletter',
  'description' => 'Send any pages as Newsletter and provide statistics on opened emails and clicked links.',
  'category' => 'module',
  'shy' => 0,
  'version' => '2.2.3',
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
      'typo3' => '6.1.0-6.2.99',
      'scheduler' => '1.1.0',
    ),
    'conflicts' => '',
    'suggests' =>
    array (
    ),
  ),
  'user' => 'acrivelli',
  'comment' => 'Minor fix to ensure that test button is always visible. See https://forge.typo3.org/news/758 for important announcement.',
);

?>
<?php

$EM_CONF[$_EXTKEY] = array(
    'title' => 'Newsletter',
    'description' => 'Send any pages as Newsletter and provide statistics on opened emails and clicked links.',
    'category' => 'module',
    'shy' => 0,
    'version' => '2.5.0',
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
    'CGLcompliance' => null,
    'CGLcompliance_note' => null,
    'constraints' => array(
        'depends' => array(
            'cms' => '',
            'php' => '5.3.7-0.0.0',
            'typo3' => '6.1.0-7.99.99',
            'scheduler' => '6.1.0',
        ),
        'conflicts' => '',
        'suggests' => array(
        ),
    ),
);

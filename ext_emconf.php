<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Newsletter',
    'description' => 'Send any pages as Newsletter and provide statistics on opened emails and clicked links.',
    'category' => 'module',
    'shy' => 0,
    'version' => '3.0.1',
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
    'constraints' => [
        'depends' => [
            'php' => '5.6.0-0.0.0',
            'typo3' => '6.2.0-8.99.99',
            'scheduler' => '6.2.0',
        ],
    ],
];

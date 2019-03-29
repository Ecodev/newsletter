<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Newsletter',
    'description' => 'Send any pages as Newsletter and provide statistics on opened emails and clicked links.',
    'category' => 'module',
    'version' => '4.0.0',
    'state' => 'stable',
    'uploadfolder' => 1,
    'author' => 'Ecodev',
    'author_email' => 'contact@ecodev.ch',
    'author_company' => 'Ecodev',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-9.5.99'
        ],
    ],
];

<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Newsletter',
    'description' => 'Send any pages as Newsletter and provide statistics on opened emails and clicked links.',
    'category' => 'module',
    'version' => '3.1.0',
    'state' => 'stable',
    'uploadfolder' => 1,
    'author' => 'Ecodev',
    'author_email' => 'contact@ecodev.ch',
    'author_company' => 'Ecodev',
    'constraints' => [
        'depends' => [
            'php' => '5.6.0-0.0.0',
            'typo3' => '6.2.0-8.99.99',
            'scheduler' => '6.2.0',
        ],
    ],
];

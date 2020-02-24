<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Demo: Slug field for content elements',
    'description' => 'Adds a slug field for readable anchors to TYPO3 content elements. The anchor can be generated from the current header or be set freely.',
    'category' => 'example',
    'author' => 'Sebastian Klein',
    'author_email' => 'sebastian@sebkln.de',
    'state' => 'example',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-9.5.99',
            'fluid_styled_content' => ''
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];

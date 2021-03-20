<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Speaking URL fragments (anchors)',
    'description' => 'Adds a slug field for human-readable anchors ("domain.com/page/#my-section") to TYPO3 content elements. By default, this anchor is rendered as the header\'s id attribute.',
    'category' => 'fe',
    'author' => 'Sebastian Klein',
    'author_email' => 'sebastian@sebkln.de',
    'state' => 'stable',
    'version' => '1.2.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-11.1.99',
            'fluid_styled_content' => ''
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];

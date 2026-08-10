<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

$fields = [
    'tx_content_slug_fragment' => [
        'exclude' => true,
        'label' => 'LLL:EXT:content_slug/Resources/Private/Language/locallang_db.xlf:tt_content.tx_content_slug_fragment',
        'config' => [
            'type' => 'input',
            'size' => 50,
            'max' => 80,
            'eval' => 'trim,Sebkln\\ContentSlug\\Evaluation\\FragmentEvaluation,uniqueInPid',
            'default' => '',
            'fieldControl' => [
                'importControl' => [
                    'renderType' => 'generateFragmentFromHeaderControl'
                ]
            ]
        ],
    ],
    'tx_content_slug_link' => [
        'exclude' => true,
        'label' => 'LLL:EXT:content_slug/Resources/Private/Language/locallang_db.xlf:tt_content.tx_content_slug_link',
        'config' => [
            'type' => 'check',
            'items' => [
                [
                    'label' => 'LLL:EXT:content_slug/Resources/Private/Language/locallang_db.xlf:tt_content.tx_content_slug_link.check',
                    'value' => ''
                ],
            ],
        ],
    ]
];

ExtensionManagementUtility::addTCAcolumns('tt_content', $fields);

ExtensionManagementUtility::addFieldsToPalette(
    'tt_content',
    'headers',
    '--linebreak--, tx_content_slug_fragment, tx_content_slug_link',
    'after:header_link'
);

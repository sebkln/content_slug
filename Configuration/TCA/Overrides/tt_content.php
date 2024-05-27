<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

// Configure the new field:
$fields = array(
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
);

// Add the new fields to an existing table definition:
ExtensionManagementUtility::addTCAcolumns('tt_content', $fields);

// Add the new fields to an existing palette:
ExtensionManagementUtility::addFieldsToPalette(
    'tt_content', // Table for TYPO3 content elements
    'headers', // Existing palette for header related fields
    '--linebreak--, tx_content_slug_fragment, tx_content_slug_link', // The new fields, rendered in a new line
    'after:header_link' // Position of the new field
);

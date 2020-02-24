<?php
defined('TYPO3_MODE') or die();

// Configure the new field:
$fields = array(
    'tx_content_slug_anchor' => [
        'exclude' => true,
        'label' => 'LLL:EXT:content_slug/Resources/Private/Language/locallang_db.xlf:tt_content.tx_content_slug_anchor',
        'config' => [
            'type' => 'slug',
            'size' => 50,
            'max' => 80,
            'generatorOptions' => [
                'fields' => ['header'],
                'fieldSeparator' => '/',
                'prefixParentPageSlug' => false
            ],
            'fallbackCharacter' => '-',
            'eval' => 'uniqueInPid',
            'default' => ''
        ],
    ]
);

// Add the new field to an existing table definition:
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $fields);

// Add the new field to an existing palette:
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'tt_content', // Table for TYPO3 content elements
    'headers', // Existing palette for header related fields
    '--linebreak--, tx_content_slug_anchor', // The new field, rendered in a new line
    'after:header_link' // Position of the new field
);

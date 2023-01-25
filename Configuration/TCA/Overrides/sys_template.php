<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

ExtensionManagementUtility::addStaticFile(
    'content_slug',
    'Configuration/TypoScript',
    'Speaking URL fragments (anchors)'
);

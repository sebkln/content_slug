<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'content_slug',
    'Configuration/TypoScript',
    'Speaking URL fragments (anchors)'
);

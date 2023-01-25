<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

ExtensionManagementUtility::addLLrefForTCAdescr(
    'tt_content',
    'EXT:content_slug/Resources/Private/Language/locallang_csh_tt_content.xlf'
);

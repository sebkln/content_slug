<?php

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

(static function () {
    // TODO: Remove CSH when support for TYPO3 v11 is dropped.
    if ((new Typo3Version())->getMajorVersion() < 12) {
        ExtensionManagementUtility::addLLrefForTCAdescr(
            'tt_content',
            'EXT:content_slug/Resources/Private/Language/locallang_csh_tt_content.xlf'
        );
    }
})();

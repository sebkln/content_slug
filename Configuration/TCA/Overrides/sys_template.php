<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

ExtensionManagementUtility::addStaticFile(
    'content_slug',
    'Configuration/TypoScript',
    'Speaking URL fragments (Basic configuration)'
);

ExtensionManagementUtility::addStaticFile(
    'content_slug',
    'Configuration/TypoScript/FluidStyledContent',
    'Speaking URL fragments (for Fluid Styled Content)'
);

ExtensionManagementUtility::addStaticFile(
    'content_slug',
    'Configuration/TypoScript/ContentBlocks',
    'Speaking URL fragments (for Content Blocks)'
);

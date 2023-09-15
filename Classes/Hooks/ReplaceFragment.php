<?php

namespace Sebkln\ContentSlug\Hooks;

/*
 * This file is part of the package sebkln/content_slug
 *
 * Copyright (c) 2021 Sebastian Klein <sebastian@sebkln.de>
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Frontend\ContentObject\TypolinkModifyLinkConfigForPageLinksHookInterface;

/**
 * Replaces the default fragment (like "#c123") with the human-readable version, if given.
 * This applies to links in the RTE, as well as TCA fields with renderType "inputLink".
 *
 * TODO: Remove hook when support for TYPO3 v11 is dropped.
 *
 * @package Sebkln\ContentSlug\Hooks
 */
class ReplaceFragment implements TypolinkModifyLinkConfigForPageLinksHookInterface
{
    protected ConfigurationManagerInterface $configurationManager;

    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager): void
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * @param array $linkConfiguration The link configuration (for options see TSRef -> typolink)
     * @param array $linkDetails Additional information for the link
     * @param array $pageRow The complete page row for the page to link to
     * @return array The modified $linkConfiguration
     */
    public function modifyPageLinkConfiguration(array $linkConfiguration, array $linkDetails, array $pageRow): array
    {
        if ($GLOBALS['TSFE'] && isset($linkDetails['fragment']) && is_numeric($linkDetails['fragment'])) {
            // 1. Get TypoScript configuration:
            $settings = $this->configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
            );
            $replaceFragmentInPageLinks = $settings['plugin.']['tx_contentslug.']['settings.']['replaceFragmentInPageLinks'] ?? 0;

            // 2. Check if the hook should be used:
            if ((int)$replaceFragmentInPageLinks === 1) {
                // 3. Get localized data array of the linked content element using "ContentObjectRenderer->getRecords()":
                $queryConfiguration = [
                    'uidInList' => $linkDetails['fragment'],
                    'pidInList' => 0,
                    'languageField' => 'sys_language_uid',
                    'max' => 1,
                ];

                /** @var ContentObjectRenderer $recordContentObjectRenderer */
                $recordContentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
                $record = current($recordContentObjectRenderer->getRecords('tt_content', $queryConfiguration));

                // 4. Process the new fragment:
                $fragmentcObj = $settings['lib.']['contentElement.']['variables.']['fragmentIdentifier'];
                $fragmentConf = $settings['lib.']['contentElement.']['variables.']['fragmentIdentifier.'];

                if (is_array($record) && (int)$record['header_layout'] !== 100) {
                    $recordContentObjectRenderer->start($record, 'tt_content');
                    $newFragment = $recordContentObjectRenderer->cObjGetSingle($fragmentcObj, $fragmentConf, 'newFragment');

                    $linkConfiguration['section.']['override'] = $newFragment;
                }
            }
        }

        return $linkConfiguration;
    }
}

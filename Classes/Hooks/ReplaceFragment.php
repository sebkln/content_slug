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

use Doctrine\DBAL\Exception as DBALException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Frontend\ContentObject\TypolinkModifyLinkConfigForPageLinksHookInterface;

/**
 * Replaces the default fragment (like "#c123") with the human-readable version, if given.
 * This applies to links in the RTE, as well as TCA fields with renderType "inputLink".
 *
 * @package Sebkln\ContentSlug\Hooks
 */
class ReplaceFragment implements TypolinkModifyLinkConfigForPageLinksHookInterface
{
    protected ConfigurationManagerInterface $configurationManager;

    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager): void
    {
        $this->configurationManager = $configurationManager;
        $this->typoScriptSetup = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
    }

    /**
     * @param array $linkConfiguration The link configuration (for options see TSRef -> typolink)
     * @param array $linkDetails Additional information for the link
     * @param array $pageRow The complete page row for the page to link to
     * @return array The modified $linkConfiguration
     * @throws DBALException
     */
    public function modifyPageLinkConfiguration(array $linkConfiguration, array $linkDetails, array $pageRow): array
    {
        if (isset($linkDetails['fragment']) && is_numeric($linkDetails['fragment'])) {
            // 1. Get TypoScript configuration:
            $settings = $this->configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
            );
            $replaceFragmentInPageLinks = $settings['plugin.']['tx_contentslug.']['settings.']['replaceFragmentInPageLinks'];

            // 2. Check if the hook should be used:
            if ((int)$replaceFragmentInPageLinks === 1) {
                // 3. Get data array of the linked content element:
                $contentId = $linkDetails['fragment'];
                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
                $queryResult = $queryBuilder
                    ->select('*')
                    ->from('tt_content')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($contentId, \PDO::PARAM_INT)
                        )
                    )
                    ->execute()
                    ->fetch();

                // 4. Process the new fragment:
                if (!empty($queryResult['tx_content_slug_fragment']) && ((int)$queryResult['header_layout'] !== 100)) {
                    $fragmentcObj = $settings['lib.']['contentElement.']['variables.']['fragmentIdentifier'];
                    $fragmentConf = $settings['lib.']['contentElement.']['variables.']['fragmentIdentifier.'];

                    /** @var ContentObjectRenderer $recordContentObjectRenderer */
                    $recordContentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
                    $recordContentObjectRenderer->start($queryResult, 'tt_content');
                    $recordContentObjectRenderer->setCurrentVal((string)$contentId);
                    $newFragment = $recordContentObjectRenderer->cObjGetSingle($fragmentcObj, $fragmentConf, 'newFragment');

                    $linkConfiguration['section.']['override'] = $newFragment;
                }
            }
        }
        return $linkConfiguration;
    }
}

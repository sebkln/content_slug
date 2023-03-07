<?php

namespace Sebkln\ContentSlug\Listener;

/*
 * This file is part of the package sebkln/content_slug
 *
 * Copyright (c) 2023 Sebastian Klein <sebastian@sebkln.de>
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Event\ModifyPageLinkConfigurationEvent;

/**
 * Replaces the default fragment (like "#c123") with the human-readable version, if given.
 * This applies to links in the RTE, as well as TCA fields with renderType "inputLink".
 *
 * @package Sebkln\ContentSlug\Listener
 */
class ModifyFragment
{
    protected ConfigurationManagerInterface $configurationManager;

    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager): void
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * @throws Exception
     */
    public function __invoke(ModifyPageLinkConfigurationEvent $event): void
    {
        $fragment = $event->getFragment();
        $fragment = substr($fragment, 1);

        if (!empty($fragment) && is_numeric($fragment)) {
            // 1. Get TypoScript configuration:
            $settings = $this->configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
            );
            $replaceFragmentInPageLinks = $settings['plugin.']['tx_contentslug.']['settings.']['replaceFragmentInPageLinks'] ?? 0;

            // 2. Check if fragment should be replaced:
            if ((int)$replaceFragmentInPageLinks === 1) {
                // 3. Get data array of the linked content element:
                $contentId = $fragment;
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
                    ->executeQuery()
                    ->fetchAssociative();

                // 4. Process the new fragment:
                if (is_array($queryResult) && (int)$queryResult['header_layout'] !== 100) {
                    $fragmentcObj = $settings['lib.']['contentElement.']['variables.']['fragmentIdentifier'];
                    $fragmentConf = $settings['lib.']['contentElement.']['variables.']['fragmentIdentifier.'];

                    /** @var ContentObjectRenderer $recordContentObjectRenderer */
                    $recordContentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
                    $recordContentObjectRenderer->start($queryResult, 'tt_content');
                    $recordContentObjectRenderer->setCurrentVal((string)$contentId);
                    $newFragment = $recordContentObjectRenderer->cObjGetSingle($fragmentcObj, $fragmentConf, 'newFragment');

                    if ($newFragment !== '') {
                        $event->setFragment($newFragment);
                    }
                }
            }
        }
    }
}

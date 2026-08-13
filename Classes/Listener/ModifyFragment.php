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

use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Event\ModifyPageLinkConfigurationEvent;

/**
 * Replaces the default fragment (like "#c123") with the human-readable version, if given.
 * This applies to links in the RTE, as well as to TCA fields of type "link".
 *
 * @package Sebkln\ContentSlug\Listener
 */
class ModifyFragment
{
    public function __invoke(ModifyPageLinkConfigurationEvent $event): void
    {
        $fragment = $event->getFragment();
        $fragment = substr($fragment, 1);

        if (is_numeric($fragment) && !empty($fragment) && $this->isFrontendRequest($event)) {
            // 1. Get TypoScript configuration:
            $request = $event->getRequest();
            $typoScript = $request->getAttribute('frontend.typoscript')->getSetupArray();
            $replaceFragmentInPageLinks = $typoScript['plugin.']['tx_contentslug.']['settings.']['replaceFragmentInPageLinks'] ?? 0;
            $checkForHiddenHeaders = $typoScript['plugin.']['tx_contentslug.']['settings.']['checkForHiddenHeaders'] ?? true;

            // 2. Check if fragment should be replaced:
            if ((int)$replaceFragmentInPageLinks === 1) {
                // 3. Get localized data array of the linked content element using "ContentObjectRenderer->getRecords()":
                $queryConfiguration = [
                    'uidInList' => $fragment,
                    'pidInList' => 0,
                    'languageField' => 'sys_language_uid',
                    'max' => 1,
                ];

                /** @var ContentObjectRenderer $recordContentObjectRenderer */
                $recordContentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
                $recordContentObjectRenderer->setRequest($event->getRequest());
                $record = current($recordContentObjectRenderer->getRecords('tt_content', $queryConfiguration));

                if (is_array($record)) {
                    // 4. Process the new fragment:
                    if (!$checkForHiddenHeaders || (int)$record['header_layout'] !== 100) {
                        $fragmentcObj = $typoScript['lib.']['contentElement.']['variables.']['fragmentIdentifier'];
                        $fragmentConf = $typoScript['lib.']['contentElement.']['variables.']['fragmentIdentifier.'];
                        $recordContentObjectRenderer->start($record, 'tt_content');
                        $newFragment = $recordContentObjectRenderer->cObjGetSingle($fragmentcObj, $fragmentConf, 'newFragment');

                        if ($newFragment !== '') {
                            $event->setFragment($newFragment);
                        }
                    }
                }
            }
        }
    }

    /**
     * The isFrontend() check is needed to exclude the Redirects backend module.
     * The Page information check is necessary to exclude actual redirects containing fragments in the target,
     * where the TypoScript setup is not available.
     *
     * @param ModifyPageLinkConfigurationEvent $event
     * @return bool
     */
    protected function isFrontendRequest(ModifyPageLinkConfigurationEvent $event): bool
    {
        $request = $event->getRequest();
        if (ApplicationType::fromRequest($request)->isFrontend()) {
            $pageInformation = $request->getAttribute('frontend.page.information');
            if ($pageInformation) {
                return $pageInformation->getId() > 0;
            }
        }
        return false;
    }
}

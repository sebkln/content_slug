<?php

namespace Sebkln\ContentSlug\DataProcessing;

/*
 * This file is part of the package sebkln/content_slug
 *
 * Copyright (c) 2021 Sebastian Klein <sebastian@sebkln.de>
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Class FragmentIdentifierProcessor
 *
 * @package Sebkln\ContentSlug\DataProcessing
 */
class FragmentIdentifierProcessor implements DataProcessorInterface
{
    protected ConfigurationManagerInterface $configurationManager;

    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager): void
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        // Don't set a custom fragment for hidden headers:
        if ((int)$processedData['data']['header_layout'] === 100) {
            return $processedData;
        }

        $settings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );

        $fragmentcObj = $settings['lib.']['contentElement.']['variables.']['fragmentIdentifier'];
        $fragmentConf = $settings['lib.']['contentElement.']['variables.']['fragmentIdentifier.'];
        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, 'fragmentIdentifier');

        if ($fragmentConf) {
            $processedData[$targetVariableName] = $cObj->cObjGetSingle($fragmentcObj, $fragmentConf, 'fragmentIdentifier');
        }

        return $processedData;
    }
}

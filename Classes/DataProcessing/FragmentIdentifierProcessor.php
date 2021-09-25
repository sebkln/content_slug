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

use TYPO3\CMS\Core\Utility\GeneralUtility;
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
    /**
     * @var ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager)
    {
        $this->configurationManager = $configurationManager;
        $this->typoScriptSetup = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
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
    ) {
        // Don't set a custom fragment for hidden headers:
        if ($processedData['data']['header_layout'] == 100 || empty($processedData['data']['tx_content_slug_fragment'])) {
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

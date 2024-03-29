<?php

use Sebkln\ContentSlug\Evaluation\FragmentEvaluation;
use Sebkln\ContentSlug\FormEngine\FieldControl\GenerateFragmentFromHeaderControl;
use Sebkln\ContentSlug\Hooks\ReplaceFragment;
use TYPO3\CMS\Core\Information\Typo3Version;

defined('TYPO3') or die();

(static function () {
    // Register the class to be available in 'eval' of TCA:
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][FragmentEvaluation::class] = '';

    // Register new render type to copy current header to the fragment field:
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1587208269] = [
        'nodeName' => 'generateFragmentFromHeaderControl',
        'priority' => 30,
        'class' => GenerateFragmentFromHeaderControl::class
    ];

    // TODO: Remove hook when support for TYPO3 v11 is dropped.
    if ((new Typo3Version())->getMajorVersion() < 12) {
        // Register hook to overwrite fragments in page links (RTE and TCA):
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typolinkProcessing']['typolinkModifyParameterForPageLinks'][] = ReplaceFragment::class;
    }
})();

<?php

use Sebkln\ContentSlug\Evaluation\FragmentEvaluation;
use Sebkln\ContentSlug\FormEngine\FieldControl\GenerateFragmentFromHeaderControl;

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
})();

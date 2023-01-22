<?php

defined('TYPO3') or die();

// Register the class to be available in 'eval' of TCA:
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals']['Sebkln\\ContentSlug\\Evaluation\\FragmentEvaluation'] = '';

// Register new render type to copy current header to the fragment field:
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1587208269] = [
    'nodeName' => 'generateFragmentFromHeaderControl',
    'priority' => 30,
    'class' => \Sebkln\ContentSlug\FormEngine\FieldControl\GenerateFragmentFromHeaderControl::class
];

// Register hook to overwrite fragments in page links (RTE and TCA):
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typolinkProcessing']['typolinkModifyParameterForPageLinks'][] = \Sebkln\ContentSlug\Hooks\ReplaceFragment::class;

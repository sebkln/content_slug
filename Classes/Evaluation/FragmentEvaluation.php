<?php

namespace Sebkln\ContentSlug\Evaluation;

/*
 * This file is part of the package sebkln/content_slug
 *
 * Copyright (c) 2020 Sebastian Klein <sebastian@sebkln.de>
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class for field value validation/evaluation to be used in 'eval' of TCA
 */
class FragmentEvaluation
{
    /**
     * JavaScript code for client side validation/evaluation.
     * This function is called when the blur event is fired (the field has lost focus).
     *
     * @return JavaScriptModuleInstruction JavaScript code for client side validation/evaluation
     */
    public function returnFieldJS(): JavaScriptModuleInstruction
    {
        return JavaScriptModuleInstruction::forRequireJS('TYPO3/CMS/ContentSlug/FragmentEvaluation');
    }

    /**
     * Server-side validation/evaluation on saving the record.
     *
     * @param string $value The field value to be evaluated
     * @return string Evaluated field value
     */
    public function evaluateFieldValue(string $value): string
    {
        return $this->sanitizeFragment($value);
    }

    /**
     * Server-side validation/evaluation on opening the record.
     * Currently, the value is returned here without any evaluation.
     *
     * @param array $parameters Array with key 'value' containing the field value from the database
     * @return string Evaluated field value
     */
    public function deevaluateFieldValue(array $parameters): string
    {
        return $parameters['value'];
    }

    /**
     * Cleans a slug value, so it can be used as an anchor in the URL.
     * This is a reduced and adapted version of the SlugHelper sanitize method.
     *
     * Admissible characters for HTML id attributes / fragment identifiers are:
     * - ASCII characters
     * - digits
     * - underscores
     * - hyphens
     * - periods
     *
     * @param string $slug
     * @return string
     */
    public function sanitizeFragment(string $slug): string
    {
        // Convert to lowercase and remove tags:
        $slug = mb_strtolower($slug, 'utf-8');
        $slug = strip_tags($slug);

        // Convert space characters to the hyphen character:
        $fallbackCharacter = '-';
        $slug = preg_replace('/[ \t\x{00A0}]+/u', $fallbackCharacter, $slug);

        // Convert extended letters to ASCII equivalents.
        // The specCharsToASCII() converts "€" to "EUR".
        $slug = GeneralUtility::makeInstance(CharsetConverter::class)->specCharsToASCII('utf-8', $slug);

        // Keep only valid characters:
        $slug = preg_replace('/[^\p{L}\p{M}0-9\-_.' . preg_quote($fallbackCharacter) . ']/u', '', $slug);

        // Convert multiple fallback characters to a single one:
        $slug = preg_replace('/' . preg_quote($fallbackCharacter) . '{2,}/', $fallbackCharacter, $slug);

        // Ensure slug is lower cased after all replacement was done:
        $slug = mb_strtolower($slug, 'utf-8');

        return $slug;
    }
}

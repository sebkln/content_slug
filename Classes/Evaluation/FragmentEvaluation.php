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
     * The array containing the diacritics map is taken from the DataTables plugin 'Diacritics-neutralise':
     * https://github.com/DataTables/Plugins/blob/master/filtering/type-based/diacritics-neutralise.js
     * Author: David Konrad.
     *
     * The not required uppercase characters were removed, some currencies were added instead.
     *
     * @return string JavaScript code for client side validation/evaluation
     */
    public function returnFieldJS(): string
    {
        return "
        // Convert to lowercase and remove tags:
        value = value.toLowerCase();
        value = value.replace(/(<([^>]+)>)/ig, '');

        // Convert space characters to the hyphen character:
        value = value.replace(/[ \t\/\u00A0]+/ug, '-');

        // Convert diacritics and most common currencies:
        const lowerCaseDiacriticsMap = [
            {'base':'a',      'chars':/[\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250]/g},
            {'base':'aa',     'chars':/[\uA733]/g},
            {'base':'ae',     'chars':/[\u00E4\u00E6\u01FD\u01E3]/g},
            {'base':'ao',     'chars':/[\uA735]/g},
            {'base':'au',     'chars':/[\uA737]/g},
            {'base':'av',     'chars':/[\uA739\uA73B]/g},
            {'base':'ay',     'chars':/[\uA73D]/g},
            {'base':'b',      'chars':/[\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253]/g},
            {'base':'c',      'chars':/[\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184]/g},
            {'base':'d',      'chars':/[\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A]/g},
            {'base':'dz',     'chars':/[\u01F3\u01C6]/g},
            {'base':'e',      'chars':/[\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD]/g},
            {'base':'f',      'chars':/[\u0066\u24D5\uFF46\u1E1F\u0192\uA77C]/g},
            {'base':'g',      'chars':/[\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F]/g},
            {'base':'h',      'chars':/[\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265]/g},
            {'base':'hv',     'chars':/[\u0195]/g},
            {'base':'i',      'chars':/[\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131]/g},
            {'base':'j',      'chars':/[\u006A\u24D9\uFF4A\u0135\u01F0\u0249]/g},
            {'base':'k',      'chars':/[\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3]/g},
            {'base':'l',      'chars':/[\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747]/g},
            {'base':'lj',     'chars':/[\u01C9]/g},
            {'base':'m',      'chars':/[\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F]/g},
            {'base':'n',      'chars':/[\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5]/g},
            {'base':'nj',     'chars':/[\u01CC]/g},
            {'base':'o',      'chars':/[\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275]/g},
            {'base':'oe',     'chars': /[\u00F6\u0153]/g},
            {'base':'oi',     'chars':/[\u01A3]/g},
            {'base':'ou',     'chars':/[\u0223]/g},
            {'base':'oo',     'chars':/[\uA74F]/g},
            {'base':'p',      'chars':/[\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755]/g},
            {'base':'q',      'chars':/[\u0071\u24E0\uFF51\u024B\uA757\uA759]/g},
            {'base':'r',      'chars':/[\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783]/g},
            {'base':'s',      'chars':/[\u0073\u24E2\uFF53\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B]/g},
            {'base':'ss',     'chars':/[\u00DF]/g},
            {'base':'t',      'chars':/[\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787]/g},
            {'base':'tz',     'chars':/[\uA729]/g},
            {'base':'u',      'chars':/[\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289]/g},
            {'base':'ue',     'chars':/[\u00FC]/g},
            {'base':'v',      'chars':/[\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C]/g},
            {'base':'vy',     'chars':/[\uA761]/g},
            {'base':'w',      'chars':/[\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73]/g},
            {'base':'x',      'chars':/[\u0078\u24E7\uFF58\u1E8B\u1E8D]/g},
            {'base':'y',      'chars':/[\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF]/g},
            {'base':'z',      'chars':/[\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763]/g},
            {'base':'eur',    'chars':/[\u20AC]/g},
            {'base':'pound',  'chars':/[\u00A3]/g},
            {'base':'yen',    'chars':/[\u00A5]/g},
            {'base':'dollar', 'chars':/[\u0024]/g}
        ];
        for (var i = 0; i < lowerCaseDiacriticsMap.length; i++) {
            value = value.replace(lowerCaseDiacriticsMap[i].chars, lowerCaseDiacriticsMap[i].base);
        }

        // Keep only valid characters.
        value = value.replace(/[^\p{L}\p{M}0-9\-_.]/ug, '');

        // Convert multiple fallback characters to a single one:
        value = value.replace(/-+/g, '-');

        // Ensure slug is lower cased after all replacement was done:
        value = value.toLowerCase();

        return value;";
    }

    /**
     * Server-side validation/evaluation on saving the record.
     *
     * @param string $value The field value to be evaluated
     * @return string Evaluated field value
     */
    public function evaluateFieldValue($value)
    {
        $value = $this->sanitizeFragment($value);
        return $value;
    }

    /**
     * Server-side validation/evaluation on opening the record.
     * Currently, the value is returned here without any evaluation.
     *
     * @param array $parameters Array with key 'value' containing the field value from the database
     * @return string Evaluated field value
     */
    public function deevaluateFieldValue(array $parameters)
    {
        return $parameters['value'];
    }

    /**
     * Cleans a slug value so it can be used as an achor in a URL.
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
        $fallbackCharacter = ('-');
        $slug = preg_replace('/[ \t\x{00A0}]+/u', $fallbackCharacter, $slug);

        // Convert extended letters to ASCII equivalents.
        // The specCharsToASCII() converts "€" to "EUR".
        $slug = GeneralUtility::makeInstance(CharsetConverter::class)->specCharsToASCII('utf-8', $slug);

        // Keep only valid characters:
        $slug = preg_replace('/[^\p{L}\p{M}0-9\-_.' . preg_quote($fallbackCharacter) . ']/u', '', $slug);

        // Convert multiple fallback characters to a single one:
        if ($fallbackCharacter !== '') {
            $slug = preg_replace('/' . preg_quote($fallbackCharacter) . '{2,}/', $fallbackCharacter, $slug);
        }

        // Ensure slug is lower cased after all replacement was done:
        $slug = mb_strtolower($slug, 'utf-8');

        return $slug;
    }
}

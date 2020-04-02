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
        // Until Firefox supports Unicode property escapes, this excessive regex is needed:
        value = value.replace(/[\\0-,\\/:-@\\[-^`{-\\xA9\\xAB-\\xB4\\xB6-\\xB9\\xBB-\\xBF\\xD7\\xF7\\u02C2-\\u02C5\\u02D2-\\u02DF\\u02E5-\\u02EB\\u02ED\\u02EF-\\u02FF\\u0375\\u0378\\u0379\\u037E\\u0380-\\u0385\\u0387\\u038B\\u038D\\u03A2\\u03F6\\u0482\\u0530\\u0557\\u0558\\u055A-\\u055F\\u0589-\\u0590\\u05BE\\u05C0\\u05C3\\u05C6\\u05C8-\\u05CF\\u05EB-\\u05EE\\u05F3-\\u060F\\u061B-\\u061F\\u0660-\\u066D\\u06D4\\u06DD\\u06DE\\u06E9\\u06F0-\\u06F9\\u06FD\\u06FE\\u0700-\\u070F\\u074B\\u074C\\u07B2-\\u07C9\\u07F6-\\u07F9\\u07FB\\u07FC\\u07FE\\u07FF\\u082E-\\u083F\\u085C-\\u085F\\u086B-\\u089F\\u08B5\\u08C8-\\u08D2\\u08E2\\u0964-\\u0970\\u0984\\u098D\\u098E\\u0991\\u0992\\u09A9\\u09B1\\u09B3-\\u09B5\\u09BA\\u09BB\\u09C5\\u09C6\\u09C9\\u09CA\\u09CF-\\u09D6\\u09D8-\\u09DB\\u09DE\\u09E4-\\u09EF\\u09F2-\\u09FB\\u09FD\\u09FF\\u0A00\\u0A04\\u0A0B-\\u0A0E\\u0A11\\u0A12\\u0A29\\u0A31\\u0A34\\u0A37\\u0A3A\\u0A3B\\u0A3D\\u0A43-\\u0A46\\u0A49\\u0A4A\\u0A4E-\\u0A50\\u0A52-\\u0A58\\u0A5D\\u0A5F-\\u0A6F\\u0A76-\\u0A80\\u0A84\\u0A8E\\u0A92\\u0AA9\\u0AB1\\u0AB4\\u0ABA\\u0ABB\\u0AC6\\u0ACA\\u0ACE\\u0ACF\\u0AD1-\\u0ADF\\u0AE4-\\u0AF8\\u0B00\\u0B04\\u0B0D\\u0B0E\\u0B11\\u0B12\\u0B29\\u0B31\\u0B34\\u0B3A\\u0B3B\\u0B45\\u0B46\\u0B49\\u0B4A\\u0B4E-\\u0B54\\u0B58-\\u0B5B\\u0B5E\\u0B64-\\u0B70\\u0B72-\\u0B81\\u0B84\\u0B8B-\\u0B8D\\u0B91\\u0B96-\\u0B98\\u0B9B\\u0B9D\\u0BA0-\\u0BA2\\u0BA5-\\u0BA7\\u0BAB-\\u0BAD\\u0BBA-\\u0BBD\\u0BC3-\\u0BC5\\u0BC9\\u0BCE\\u0BCF\\u0BD1-\\u0BD6\\u0BD8-\\u0BFF\\u0C0D\\u0C11\\u0C29\\u0C3A-\\u0C3C\\u0C45\\u0C49\\u0C4E-\\u0C54\\u0C57\\u0C5B-\\u0C5F\\u0C64-\\u0C7F\\u0C84\\u0C8D\\u0C91\\u0CA9\\u0CB4\\u0CBA\\u0CBB\\u0CC5\\u0CC9\\u0CCE-\\u0CD4\\u0CD7-\\u0CDD\\u0CDF\\u0CE4-\\u0CF0\\u0CF3-\\u0CFF\\u0D0D\\u0D11\\u0D45\\u0D49\\u0D4F-\\u0D53\\u0D58-\\u0D5E\\u0D64-\\u0D79\\u0D80\\u0D84\\u0D97-\\u0D99\\u0DB2\\u0DBC\\u0DBE\\u0DBF\\u0DC7-\\u0DC9\\u0DCB-\\u0DCE\\u0DD5\\u0DD7\\u0DE0-\\u0DF1\\u0DF4-\\u0E00\\u0E3B-\\u0E3F\\u0E4F-\\u0E80\\u0E83\\u0E85\\u0E8B\\u0EA4\\u0EA6\\u0EBE\\u0EBF\\u0EC5\\u0EC7\\u0ECE-\\u0EDB\\u0EE0-\\u0EFF\\u0F01-\\u0F17\\u0F1A-\\u0F34\\u0F36\\u0F38\\u0F3A-\\u0F3D\\u0F48\\u0F6D-\\u0F70\\u0F85\\u0F98\\u0FBD-\\u0FC5\\u0FC7-\\u0FFF\\u1040-\\u104F\\u1090-\\u1099\\u109E\\u109F\\u10C6\\u10C8-\\u10CC\\u10CE\\u10CF\\u10FB\\u1249\\u124E\\u124F\\u1257\\u1259\\u125E\\u125F\\u1289\\u128E\\u128F\\u12B1\\u12B6\\u12B7\\u12BF\\u12C1\\u12C6\\u12C7\\u12D7\\u1311\\u1316\\u1317\\u135B\\u135C\\u1360-\\u137F\\u1390-\\u139F\\u13F6\\u13F7\\u13FE-\\u1400\\u166D\\u166E\\u1680\\u169B-\\u169F\\u16EB-\\u16F0\\u16F9-\\u16FF\\u170D\\u1715-\\u171F\\u1735-\\u173F\\u1754-\\u175F\\u176D\\u1771\\u1774-\\u177F\\u17D4-\\u17D6\\u17D8-\\u17DB\\u17DE-\\u180A\\u180E-\\u181F\\u1879-\\u187F\\u18AB-\\u18AF\\u18F6-\\u18FF\\u191F\\u192C-\\u192F\\u193C-\\u194F\\u196E\\u196F\\u1975-\\u197F\\u19AC-\\u19AF\\u19CA-\\u19FF\\u1A1C-\\u1A1F\\u1A5F\\u1A7D\\u1A7E\\u1A80-\\u1AA6\\u1AA8-\\u1AAF\\u1AC1-\\u1AFF\\u1B4C-\\u1B6A\\u1B74-\\u1B7F\\u1BB0-\\u1BB9\\u1BF4-\\u1BFF\\u1C38-\\u1C4C\\u1C50-\\u1C59\\u1C7E\\u1C7F\\u1C89-\\u1C8F\\u1CBB\\u1CBC\\u1CC0-\\u1CCF\\u1CD3\\u1CFB-\\u1CFF\\u1DFA\\u1F16\\u1F17\\u1F1E\\u1F1F\\u1F46\\u1F47\\u1F4E\\u1F4F\\u1F58\\u1F5A\\u1F5C\\u1F5E\\u1F7E\\u1F7F\\u1FB5\\u1FBD\\u1FBF-\\u1FC1\\u1FC5\\u1FCD-\\u1FCF\\u1FD4\\u1FD5\\u1FDC-\\u1FDF\\u1FED-\\u1FF1\\u1FF5\\u1FFD-\\u2070\\u2072-\\u207E\\u2080-\\u208F\\u209D-\\u20CF\\u20F1-\\u2101\\u2103-\\u2106\\u2108\\u2109\\u2114\\u2116-\\u2118\\u211E-\\u2123\\u2125\\u2127\\u2129\\u212E\\u213A\\u213B\\u2140-\\u2144\\u214A-\\u214D\\u214F-\\u2182\\u2185-\\u2BFF\\u2C2F\\u2C5F\\u2CE5-\\u2CEA\\u2CF4-\\u2CFF\\u2D26\\u2D28-\\u2D2C\\u2D2E\\u2D2F\\u2D68-\\u2D6E\\u2D70-\\u2D7E\\u2D97-\\u2D9F\\u2DA7\\u2DAF\\u2DB7\\u2DBF\\u2DC7\\u2DCF\\u2DD7\\u2DDF\\u2E00-\\u2E2E\\u2E30-\\u3004\\u3007-\\u3029\\u3030\\u3036-\\u303A\\u303D-\\u3040\\u3097\\u3098\\u309B\\u309C\\u30A0\\u30FB\\u3100-\\u3104\\u3130\\u318F-\\u319F\\u31C0-\\u31EF\\u3200-\\u33FF\\u4DC0-\\u4DFF\\u9FFD-\\u9FFF\\uA48D-\\uA4CF\\uA4FE\\uA4FF\\uA60D-\\uA60F\\uA620-\\uA629\\uA62C-\\uA63F\\uA673\\uA67E\\uA6E6-\\uA6EF\\uA6F2-\\uA716\\uA720\\uA721\\uA789\\uA78A\\uA7C0\\uA7C1\\uA7CB-\\uA7F4\\uA828-\\uA82B\\uA82D-\\uA83F\\uA874-\\uA87F\\uA8C6-\\uA8DF\\uA8F8-\\uA8FA\\uA8FC\\uA900-\\uA909\\uA92E\\uA92F\\uA954-\\uA95F\\uA97D-\\uA97F\\uA9C1-\\uA9CE\\uA9D0-\\uA9DF\\uA9F0-\\uA9F9\\uA9FF\\uAA37-\\uAA3F\\uAA4E-\\uAA5F\\uAA77-\\uAA79\\uAAC3-\\uAADA\\uAADE\\uAADF\\uAAF0\\uAAF1\\uAAF7-\\uAB00\\uAB07\\uAB08\\uAB0F\\uAB10\\uAB17-\\uAB1F\\uAB27\\uAB2F\\uAB5B\\uAB6A-\\uAB6F\\uABEB\\uABEE-\\uABFF\\uD7A4-\\uD7AF\\uD7C7-\\uD7CA\\uD7FC-\\uF8FF\\uFA6E\\uFA6F\\uFADA-\\uFAFF\\uFB07-\\uFB12\\uFB18-\\uFB1C\\uFB29\\uFB37\\uFB3D\\uFB3F\\uFB42\\uFB45\\uFBB2-\\uFBD2\\uFD3E-\\uFD4F\\uFD90\\uFD91\\uFDC8-\\uFDEF\\uFDFC-\\uFDFF\\uFE10-\\uFE1F\\uFE30-\\uFE6F\\uFE75\\uFEFD-\\uFF20\\uFF3B-\\uFF40\\uFF5B-\\uFF65\\uFFBF-\\uFFC1\\uFFC8\\uFFC9\\uFFD0\\uFFD1\\uFFD8\\uFFD9\\uFFDD-\\uFFFF\\u{1000C}\\u{10027}\\u{1003B}\\u{1003E}\\u{1004E}\\u{1004F}\\u{1005E}-\\u{1007F}\\u{100FB}-\\u{101FC}\\u{101FE}-\\u{1027F}\\u{1029D}-\\u{1029F}\\u{102D1}-\\u{102DF}\\u{102E1}-\\u{102FF}\\u{10320}-\\u{1032C}\\u{10341}\\u{1034A}-\\u{1034F}\\u{1037B}-\\u{1037F}\\u{1039E}\\u{1039F}\\u{103C4}-\\u{103C7}\\u{103D0}-\\u{103FF}\\u{1049E}-\\u{104AF}\\u{104D4}-\\u{104D7}\\u{104FC}-\\u{104FF}\\u{10528}-\\u{1052F}\\u{10564}-\\u{105FF}\\u{10737}-\\u{1073F}\\u{10756}-\\u{1075F}\\u{10768}-\\u{107FF}\\u{10806}\\u{10807}\\u{10809}\\u{10836}\\u{10839}-\\u{1083B}\\u{1083D}\\u{1083E}\\u{10856}-\\u{1085F}\\u{10877}-\\u{1087F}\\u{1089F}-\\u{108DF}\\u{108F3}\\u{108F6}-\\u{108FF}\\u{10916}-\\u{1091F}\\u{1093A}-\\u{1097F}\\u{109B8}-\\u{109BD}\\u{109C0}-\\u{109FF}\\u{10A04}\\u{10A07}-\\u{10A0B}\\u{10A14}\\u{10A18}\\u{10A36}\\u{10A37}\\u{10A3B}-\\u{10A3E}\\u{10A40}-\\u{10A5F}\\u{10A7D}-\\u{10A7F}\\u{10A9D}-\\u{10ABF}\\u{10AC8}\\u{10AE7}-\\u{10AFF}\\u{10B36}-\\u{10B3F}\\u{10B56}-\\u{10B5F}\\u{10B73}-\\u{10B7F}\\u{10B92}-\\u{10BFF}\\u{10C49}-\\u{10C7F}\\u{10CB3}-\\u{10CBF}\\u{10CF3}-\\u{10CFF}\\u{10D28}-\\u{10E7F}\\u{10EAA}\\u{10EAD}-\\u{10EAF}\\u{10EB2}-\\u{10EFF}\\u{10F1D}-\\u{10F26}\\u{10F28}-\\u{10F2F}\\u{10F51}-\\u{10FAF}\\u{10FC5}-\\u{10FDF}\\u{10FF7}-\\u{10FFF}\\u{11047}-\\u{1107E}\\u{110BB}-\\u{110CF}\\u{110E9}-\\u{110FF}\\u{11135}-\\u{11143}\\u{11148}-\\u{1114F}\\u{11174}\\u{11175}\\u{11177}-\\u{1117F}\\u{111C5}-\\u{111C8}\\u{111CD}\\u{111D0}-\\u{111D9}\\u{111DB}\\u{111DD}-\\u{111FF}\\u{11212}\\u{11238}-\\u{1123D}\\u{1123F}-\\u{1127F}\\u{11287}\\u{11289}\\u{1128E}\\u{1129E}\\u{112A9}-\\u{112AF}\\u{112EB}-\\u{112FF}\\u{11304}\\u{1130D}\\u{1130E}\\u{11311}\\u{11312}\\u{11329}\\u{11331}\\u{11334}\\u{1133A}\\u{11345}\\u{11346}\\u{11349}\\u{1134A}\\u{1134E}\\u{1134F}\\u{11351}-\\u{11356}\\u{11358}-\\u{1135C}\\u{11364}\\u{11365}\\u{1136D}-\\u{1136F}\\u{11375}-\\u{113FF}\\u{1144B}-\\u{1145D}\\u{11462}-\\u{1147F}\\u{114C6}\\u{114C8}-\\u{1157F}\\u{115B6}\\u{115B7}\\u{115C1}-\\u{115D7}\\u{115DE}-\\u{115FF}\\u{11641}-\\u{11643}\\u{11645}-\\u{1167F}\\u{116B9}-\\u{116FF}\\u{1171B}\\u{1171C}\\u{1172C}-\\u{117FF}\\u{1183B}-\\u{1189F}\\u{118E0}-\\u{118FE}\\u{11907}\\u{11908}\\u{1190A}\\u{1190B}\\u{11914}\\u{11917}\\u{11936}\\u{11939}\\u{1193A}\\u{11944}-\\u{1199F}\\u{119A8}\\u{119A9}\\u{119D8}\\u{119D9}\\u{119E2}\\u{119E5}-\\u{119FF}\\u{11A3F}-\\u{11A46}\\u{11A48}-\\u{11A4F}\\u{11A9A}-\\u{11A9C}\\u{11A9E}-\\u{11ABF}\\u{11AF9}-\\u{11BFF}\\u{11C09}\\u{11C37}\\u{11C41}-\\u{11C71}\\u{11C90}\\u{11C91}\\u{11CA8}\\u{11CB7}-\\u{11CFF}\\u{11D07}\\u{11D0A}\\u{11D37}-\\u{11D39}\\u{11D3B}\\u{11D3E}\\u{11D48}-\\u{11D5F}\\u{11D66}\\u{11D69}\\u{11D8F}\\u{11D92}\\u{11D99}-\\u{11EDF}\\u{11EF7}-\\u{11FAF}\\u{11FB1}-\\u{11FFF}\\u{1239A}-\\u{1247F}\\u{12544}-\\u{12FFF}\\u{1342F}-\\u{143FF}\\u{14647}-\\u{167FF}\\u{16A39}-\\u{16A3F}\\u{16A5F}-\\u{16ACF}\\u{16AEE}\\u{16AEF}\\u{16AF5}-\\u{16AFF}\\u{16B37}-\\u{16B3F}\\u{16B44}-\\u{16B62}\\u{16B78}-\\u{16B7C}\\u{16B90}-\\u{16E3F}\\u{16E80}-\\u{16EFF}\\u{16F4B}-\\u{16F4E}\\u{16F88}-\\u{16F8E}\\u{16FA0}-\\u{16FDF}\\u{16FE2}\\u{16FE5}-\\u{16FEF}\\u{16FF2}-\\u{16FFF}\\u{187F8}-\\u{187FF}\\u{18CD6}-\\u{18CFF}\\u{18D09}-\\u{1AFFF}\\u{1B11F}-\\u{1B14F}\\u{1B153}-\\u{1B163}\\u{1B168}-\\u{1B16F}\\u{1B2FC}-\\u{1BBFF}\\u{1BC6B}-\\u{1BC6F}\\u{1BC7D}-\\u{1BC7F}\\u{1BC89}-\\u{1BC8F}\\u{1BC9A}-\\u{1BC9C}\\u{1BC9F}-\\u{1D164}\\u{1D16A}-\\u{1D16C}\\u{1D173}-\\u{1D17A}\\u{1D183}\\u{1D184}\\u{1D18C}-\\u{1D1A9}\\u{1D1AE}-\\u{1D241}\\u{1D245}-\\u{1D3FF}\\u{1D455}\\u{1D49D}\\u{1D4A0}\\u{1D4A1}\\u{1D4A3}\\u{1D4A4}\\u{1D4A7}\\u{1D4A8}\\u{1D4AD}\\u{1D4BA}\\u{1D4BC}\\u{1D4C4}\\u{1D506}\\u{1D50B}\\u{1D50C}\\u{1D515}\\u{1D51D}\\u{1D53A}\\u{1D53F}\\u{1D545}\\u{1D547}-\\u{1D549}\\u{1D551}\\u{1D6A6}\\u{1D6A7}\\u{1D6C1}\\u{1D6DB}\\u{1D6FB}\\u{1D715}\\u{1D735}\\u{1D74F}\\u{1D76F}\\u{1D789}\\u{1D7A9}\\u{1D7C3}\\u{1D7CC}-\\u{1D9FF}\\u{1DA37}-\\u{1DA3A}\\u{1DA6D}-\\u{1DA74}\\u{1DA76}-\\u{1DA83}\\u{1DA85}-\\u{1DA9A}\\u{1DAA0}\\u{1DAB0}-\\u{1DFFF}\\u{1E007}\\u{1E019}\\u{1E01A}\\u{1E022}\\u{1E025}\\u{1E02B}-\\u{1E0FF}\\u{1E12D}-\\u{1E12F}\\u{1E13E}-\\u{1E14D}\\u{1E14F}-\\u{1E2BF}\\u{1E2F0}-\\u{1E7FF}\\u{1E8C5}-\\u{1E8CF}\\u{1E8D7}-\\u{1E8FF}\\u{1E94C}-\\u{1EDFF}\\u{1EE04}\\u{1EE20}\\u{1EE23}\\u{1EE25}\\u{1EE26}\\u{1EE28}\\u{1EE33}\\u{1EE38}\\u{1EE3A}\\u{1EE3C}-\\u{1EE41}\\u{1EE43}-\\u{1EE46}\\u{1EE48}\\u{1EE4A}\\u{1EE4C}\\u{1EE50}\\u{1EE53}\\u{1EE55}\\u{1EE56}\\u{1EE58}\\u{1EE5A}\\u{1EE5C}\\u{1EE5E}\\u{1EE60}\\u{1EE63}\\u{1EE65}\\u{1EE66}\\u{1EE6B}\\u{1EE73}\\u{1EE78}\\u{1EE7D}\\u{1EE7F}\\u{1EE8A}\\u{1EE9C}-\\u{1EEA0}\\u{1EEA4}\\u{1EEAA}\\u{1EEBC}-\\u{1FFFF}\\u{2A6DE}-\\u{2A6FF}\\u{2B735}-\\u{2B73F}\\u{2B81E}\\u{2B81F}\\u{2CEA2}-\\u{2CEAF}\\u{2EBE1}-\\u{2F7FF}\\u{2FA1E}-\\u{2FFFF}\\u{3134B}-\\u{E00FF}\\u{E01F0}-\\u{10FFFF}]/ug, '');

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

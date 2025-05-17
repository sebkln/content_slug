/**
 * @exports TYPO3/CMS/ContentSlug/FillFragment
 */
define(
  ['require', 'TYPO3/CMS/ContentSlug/FragmentEvaluation'],
  function (require, FragmentEvaluation) {
    'use strict';

    let FillFragment = {};

    /**
     * When the 'Generate anchor' button is clicked, the anchor/fragment field gets filled with the current content of the header field.
     * The 'FragmentEvaluation' function is used for an immediate evaluation of the fragment.
     * 'change' event is needed to successfully save the changes to the database. It also triggers the 'Unsaved changes' modal for this field.
     */
    FillFragment.initializeEvents = function () {
      const fragmentBtns = document.querySelectorAll('.btn-fragment');

        fragmentBtns.forEach(fragmentBtn => {
          const elemId = fragmentBtn.dataset.uid,
          headerField = document.querySelector('[data-formengine-input-name="data[tt_content][' + elemId + '][header]"]'),
          fragmentField = document.querySelector('[data-formengine-input-name="data[tt_content][' + elemId + '][tx_content_slug_fragment]"]');

          fragmentBtn.addEventListener('click', function (evt) {
            evt.preventDefault();
            fragmentField.value = FragmentEvaluation.evaluateFragment(headerField.value);
            fragmentField.dispatchEvent(new Event('change', {bubbles: true, cancelable: true}));
          });
        })
    };

    FillFragment.initializeEvents();

    return FillFragment;
});

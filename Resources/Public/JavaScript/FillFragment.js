import DocumentService from '@typo3/core/document-service.js';
import FragmentEvaluation from '@sebkln/content-slug/FragmentEvaluation.js';

class FillFragment {
    constructor() {
        DocumentService.ready().then((() => {
            this.fillFragmentFromHeader();
        }))
    }

    fillFragmentFromHeader() {
        let FillFragment = {};

        /**
         * When the 'Generate anchor' button is clicked, the anchor/fragment field gets filled with the current content of the header field.
         * The 'FragmentEvaluation' function is used for an immediate evaluation of the fragment.
         * 'change' event is needed to successfully save the changes to the database. It also triggers the 'Unsaved changes' modal for this field.
         */
        FillFragment.initializeEvents = function () {
            const fragmentBtn = document.querySelector('.btn-fragment'),
                elemId = fragmentBtn.dataset.uid,
                headerField = document.querySelector('[data-formengine-input-name="data[tt_content][' + elemId + '][header]"]'),
                fragmentField = document.querySelector('[data-formengine-input-name="data[tt_content][' + elemId + '][tx_content_slug_fragment]"]');

            fragmentBtn.addEventListener('click', function (evt) {
                evt.preventDefault();
                fragmentField.value = FragmentEvaluation.evaluateFragment(headerField.value);
                // fragmentField.value = headerField.value;
                fragmentField.dispatchEvent(new Event('change', {bubbles: true, cancelable: true}));
            });
        };

        FillFragment.initializeEvents();
    }
}

export default new FillFragment;

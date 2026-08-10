.. _configuration-site-settings:

=============
Site Settings
=============

If you included one of the Site sets, the extension can be configured via Site settings.

.. confval-menu::
   :name: confval-site-settings
   :type:
   :default:
   :display: table

   .. confval:: plugin.tx_contentslug.settings.renderPrefix
      :name: site-setting-renderPrefix
      :type: :ref:`t3coreapi:confval:site-setting-type-bool`
      :default: true

      Enables the prefix to the human-readable URL fragment.
      By default, the content element's uid is prepended as follows:

      .. code-block:: html

         c<uid>-<human-readable-fragment>
         c123-section-of-interest

      You can customize the prefix in :confval:`plugin.tx_contentslug.urlFragmentPrefix<ts-setup-urlFragmentPrefix>`

   .. confval:: plugin.tx_contentslug.settings.renderSuffix
      :name: site-setting-renderSuffix
      :type: :ref:`t3coreapi:confval:site-setting-type-bool`
      :default: false

      Enables the suffix to the human-readable URL fragment.
      By default, the content element's uid will be appended as follows:

      .. code-block:: html

         <human-readable-fragment>-<uid>
         section-of-interest-123

      You can customize the suffix in :confval:`plugin.tx_contentslug.urlFragmentSuffix<ts-setup-urlFragmentSuffix>`

   .. confval:: plugin.tx_contentslug.settings.replaceFragmentInPageLinks
      :name: site-setting-replaceFragmentInPageLinks
      :type: :ref:`t3coreapi:confval:site-setting-type-bool`
      :default: true

      When activated, fragment links set in the RTE or in TCA fields of type
      :php:`link` are replaced with the human-readable fragment identifier.

   .. confval:: plugin.tx_contentslug.settings.checkForHiddenHeaders
      :name: site-setting-checkForHiddenHeaders
      :type: :ref:`t3coreapi:confval:site-setting-type-bool`
      :default: true

      If disabled, fragment links are replaced even if the content element's
      header is hidden. Use this with care!
      See :confval:`TypoScript Setup Reference<ts-setup-checkForHiddenHeaders>` for details.


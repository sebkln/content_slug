.. include:: ../../Includes.txt


.. _configuration-constants:

==============================
TypoScript Constants Reference
==============================

:typoscript:`plugin.tx_contentslug`

.. container:: ts-properties

   ==================================== ===================================== ========
   Property                             Data type                             Default
   ==================================== ===================================== ========
   settings.renderPrefix_               :ref:`t3tsref:data-type-boolean`      1
   settings.renderSuffix_               :ref:`t3tsref:data-type-boolean`      0
   settings.replaceFragmentInPageLinks_ :ref:`t3tsref:data-type-boolean`      1
   settings.checkForHiddenHeaders_      :ref:`t3tsref:data-type-boolean`      1
   ==================================== ===================================== ========

Property details
================

.. _settings.renderPrefix:

settings.renderPrefix
---------------------
.. container:: table-row

   Property
      settings.renderPrefix
   Data type
      boolean
   Description
      Enables the prefix to the human-readable URL fragment.
      By default, the content element's uid is prepended like:

      .. code-block:: html

         c<uid>-<human-readable-fragment>
         c123-section-of-interest

      You can customize the prefix in :typoscript:`plugin.tx_contentslug.urlFragmentPrefix`
   Default
      :typoscript:`1`

.. _settings.renderSuffix:

settings.renderSuffix
---------------------
.. container:: table-row

   Property
      settings.renderSuffix
   Data type
      boolean
   Description
      Enables the suffix to the human-readable URL fragment.
      By default, the content element's uid is appended like:

      .. code-block:: html

         <human-readable-fragment>-<uid>
         section-of-interest-123

      You can customize the suffix in :typoscript:`plugin.tx_contentslug.urlFragmentSuffix`
   Default
      :typoscript:`0`

.. _settings.replaceFragmentInPageLinks:

settings.replaceFragmentInPageLinks
-----------------------------------
.. container:: table-row

   Property
      settings.replaceFragmentInPageLinks
   Data type
      boolean
   Description
      When activated, fragment links set in the RTE or TCA fields of type
      :php:`inputLink` are replaced with the human-readable fragment identifier.
   Default
      :typoscript:`1`

.. _settings.checkForHiddenHeaders:

settings.checkForHiddenHeaders
-----------------------------------
.. container:: table-row

   Property
      settings.checkForHiddenHeaders
   Data type
      boolean
   Description
      If disabled, fragment links are replaced even if the content element's
      header is hidden. Use this with care!
      See :ref:`TypoScript Setup Reference<checkForHiddenHeaders_>` for details.
   Default
      :typoscript:`1`

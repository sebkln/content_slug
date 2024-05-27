.. include:: ../../Includes.txt


.. _configuration-typoscript:

==========================
TypoScript Setup Reference
==========================

.. contents::
   :depth: 2


Configure prefix and suffix
===========================

:typoscript:`plugin.tx_contentslug`

.. container:: ts-properties

   =========================== =================================================== ================================
   Property                    Data type                                           Default
   =========================== =================================================== ================================
   urlFragmentPrefix_          :ref:`Content Object (cObject) <data-type-cobject>` *TEXT cObject with current UID*
   urlFragmentSuffix_          :ref:`Content Object (cObject) <data-type-cobject>` *TEXT cObject with current UID*
   replaceFragmentInPageLinks_ :ref:`t3tsref:data-type-boolean`                    1
   =========================== =================================================== ================================


.. _urlFragmentPrefix:

urlFragmentPrefix
-----------------

.. container:: table-row

   Property
      urlFragmentPrefix

   Data type
      :ref:`Content Object (cObject) <data-type-cobject>`

   Description
      This cObject can be used to render a prefix for the human-readable URL fragment.

      Prefix, suffix, and fragment are assembled in the custom variable
      :ref:`fragmentIdentifier <fragmentIdentifierFluidVariable>` of ``lib.contentElement``.

      .. note::
         The prefix is **enabled** by default with the :ref:`corresponding TypoScript constant <settings.renderPrefix>`.

      **Default:**

      .. code-block:: typoscript

         plugin.tx_contentslug.urlFragmentPrefix = TEXT
         plugin.tx_contentslug.urlFragmentPrefix {
             field = uid
             stdWrap.noTrimWrap = |c|-|
             if.isTrue = {$plugin.tx_contentslug.settings.renderPrefix}
         }

      **Result:**

      .. code-block:: html

         c<uid>-<human-readable-fragment>
         c123-section-of-interest


.. _urlFragmentSuffix:

urlFragmentSuffix
-----------------

.. container:: table-row

   Property
      urlFragmentSuffix

   Data type
      :ref:`Content Object (cObject) <data-type-cobject>`

   Description
      This cObject can be used to render a suffix for the human-readable URL fragment.

      Prefix, suffix, and fragment are assembled in the custom variable
      :ref:`fragmentIdentifier <fragmentIdentifierFluidVariable>` of ``lib.contentElement``.

      .. note::
         The suffix is **disabled** by default with the :ref:`corresponding TypoScript constant <settings.renderSuffix>`.

      **Default:**

      .. code-block:: typoscript

         plugin.tx_contentslug.urlFragmentSuffix = TEXT
         plugin.tx_contentslug.urlFragmentSuffix {
             field = uid
             stdWrap.noTrimWrap = |-||
             if.isTrue = {$plugin.tx_contentslug.settings.renderSuffix}
         }

      **Result** (if activated):

      .. code-block:: html

         <human-readable-fragment>-<uid>
         section-of-interest-123


.. _replaceFragmentInPageLinks:

settings.replaceFragmentInPageLinks
-----------------------------------

.. container:: table-row

   Property
      settings.replaceFragmentInPageLinks

   Data type
     :ref:`t3tsref:data-type-boolean`

   Description
      When activated, fragment links set in the RTE or TCA fields of type
      :php:`inputLink` are replaced with the human-readable fragment identifier.
   Default
      :typoscript:`1` (per TypoScript constant)


.. _fragmentIdentifierFluidVariable:

Assemble the :typoscript:`fragmentIdentifier` variable
======================================================

This variable is available in all Fluid templates of EXT:fluid_styled_content
and allows to configure the complete URL fragment at a central place.

.. attention::
   This variable is also processed in the following classes:

   #. The custom DataProcessor :php:`FragmentIdentifierProcessor`, which will
      process the URL fragments for the "Section Index" content elements.
   #. The :php:`ModifyFragment` event listener, which allows to overwrite fragments for
      links set in the rich text editor or in TCA fields with renderType
      :php:`inputLink`.


.. code-block:: typoscript

   lib.contentElement {
       // Override default templates of fluid_styled_content:
       partialRootPaths.101 = EXT:content_slug/Resources/Private/Overrides/fluid_styled_content/Partials/
       templateRootPaths.101 = EXT:content_slug/Resources/Private/Overrides/fluid_styled_content/Templates/

       // Build a complete fragment identifier with possible prefix and suffix:
       variables {
           fragmentIdentifier = COA
           fragmentIdentifier {
               if.isTrue.field = tx_content_slug_fragment

               10 =< plugin.tx_contentslug.urlFragmentPrefix

               20 = TEXT
               20.field = tx_content_slug_fragment

               30 =< plugin.tx_contentslug.urlFragmentSuffix

               stdWrap.trim = 1
           }
       }
   }


.. _FragmentIdentifierProcessor:

Use :typoscript:`FragmentIdentifierProcessor` for "Section Index" menus
=======================================================================

The *menu* content elements of type "Section Index" are built with DataProcessors.

To get the configured :typoscript:`fragmentIdentifier` variable for each of the
linked content elements in these menus, the custom :typoscript:`FragmentIdentifierProcessor`
is needed.

.. code-block:: typoscript

   // Process 'fragmentIdentifier' variable in section menus:
   tt_content.menu_section.dataProcessing.10.dataProcessing.20.dataProcessing.5 = Sebkln\ContentSlug\DataProcessing\FragmentIdentifierProcessor
   tt_content.menu_section_pages.dataProcessing.10.dataProcessing.20.dataProcessing.5 = Sebkln\ContentSlug\DataProcessing\FragmentIdentifierProcessor


.. _postUserFunc:

Sanitize custom data with :typoscript:`postUserFunc`
====================================================

In case you append or prepend some **custom strings**, you can use the fragment
evaluation to :ref:`sanitize <editors-fragment-syntax>` the completed URL fragment again:

.. code-block:: typoscript

   urlFragmentSuffix = TEXT
   urlFragmentSuffix {
       field = subheader
       if.isTrue.field = subheader
       stdWrap.noTrimWrap = |-||
   }

   lib.contentElement.variables.fragmentIdentifier {
       stdWrap.postUserFunc = Sebkln\ContentSlug\Evaluation\FragmentEvaluation->sanitizeFragment
   }

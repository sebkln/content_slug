.. include:: ../../Includes.txt


.. _developer-tca:

=============
Adjusting TCA
=============

.. note::

   The following examples assume general knowledge of the
   :ref:`Table Configuration Array (TCA) <t3tca:start>`.

   You need to store these changes in the :ref:`sitepackage <t3sitepackage:start>`
   of your website in :file:`Configuration/TCA/Overrides/tt_content.php`.

.. contents::
   :depth: 2


.. _tca-palette:

1. Adding fragment fields to additional content elements
========================================================

The custom fields of EXT:content_slug are added to the TCA palette ``headers``.
This palette is used by most of the content elements in the TYPO3 core.

Some extensions might not use this palette, though. Instead, a reduced or
custom palette is used to display the header field with some selected
fields in the backend.

A good example is `EXT:beautyofcode <https://extensions.typo3.org/extension/beautyofcode>`__.
This useful extension by developer Felix Nagel allows to render code examples
with syntax highlighting and line numbers.
It uses the reduced palette ``header`` (mind the missing *s* at the end).


Solution A: Replace the palette
-------------------------------

One possible way to add the fragment-related fields is to replace the ``header`` palette for its CType:

.. code-block:: php

   $GLOBALS['TCA']['tt_content']['types']['beautyofcode_contentrenderer']['showitem'] = str_replace(
       '--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.header;header,',
       '--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.header;headers,',
       $GLOBALS['TCA']['tt_content']['types']['beautyofcode_contentrenderer']['showitem']
   );


Solution B: Replace the ``showitem`` section
--------------------------------------------

Note that EXT:beautyofcode also hides the palette ``appearanceLinks``, which
contains the field ``sectionIndex``.

So in this case, we can overwrite the entire ``showitem`` section to change the
header palette and insert the ``appearanceLinks`` palette as well:

.. code-block:: php
   :linenos:
   :emphasize-lines: 3,9

   $GLOBALS['TCA']['tt_content']['types']['beautyofcode_contentrenderer']['showitem'] = '
       --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.general;general,
       --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.header;headers,
       --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin,
           pi_flexform,
           bodytext;LLL:EXT:beautyofcode/Resources/Private/Language/locallang_db.xlf:code,
       --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
           --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
           --palette--;;appearanceLinks,
       --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
           --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.visibility;visibility,
           --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
       --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.extended
   ';


Solution C: Add the fragment fields to another palette
------------------------------------------------------

Some custom content element might use a completely new palette to render the
header field with some extras. In this case, you can use the TYPO3 API to
add the fields to this palette.

You need to adjust the **name** of the custom palette (line 3) and set the
**position** to *before* or *after* an existing field in this palette (line 5).

.. code-block:: php
   :linenos:
   :emphasize-lines: 3,5

   \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
       'tt_content', // Table for TYPO3 content elements
       'custom_palette', // The palette which should contain the fragment fields
       '--linebreak--, tx_content_slug_fragment, tx_content_slug_link', // The fields, rendered in a new line
       'after:some_field' // Position of the fragment fields
   );

.. warning::

   While you *could* add the fragment fields to the existing ``header`` palette,
   be aware of the consequences: this would also add them to CTypes like
   ``shortcut`` and ``html``, which won't render the header in the frontend
   (by default).


.. _tca-uniqueinpid:

2. Removing the ``uniqueInPid`` evaluation
==========================================

What does the ``uniqueInPid`` evaluation even do?
-------------------------------------------------

It's a mechanism that prevents duplicate field values on the same page.
This is **very** helpful for our URL fragments, as they **need** to be
unique on each page (otherwise the browser can't distinguish the anchor links).


Why would I even consider to remove it then!?
---------------------------------------------

Imagine a website with translated content on the same page.
The ``uniqueInPid`` evaluation doesn't distinguish between languages.
That means you can't have identical anchor links in e.g. English and German.

In practice this seldom is an issue. But it might become one
if you want to use technical terms or brand names as anchors
(e.g. ``https://www.example.org/info/#typo3``).


How to remove it from the list of eval functions
------------------------------------------------

.. caution::

   I strongly advise to only remove this evaluation on websites with few and
   well-trained editors that fully understand the consequences.

The following code removes ``uniqueInPid`` from the list of eval functions.
You mustn't remove the other two evaluations!

.. code-block:: php

   // Original:
   $GLOBALS['TCA']['tt_content']['columns']['tx_content_slug_fragment']['config']['eval'] = 'trim,Sebkln\\ContentSlug\\Evaluation\\FragmentEvaluation,uniqueInPid';

   // Remove the evaluation for all content elements:
   $GLOBALS['TCA']['tt_content']['columns']['tx_content_slug_fragment']['config']['eval'] = 'trim,Sebkln\\ContentSlug\\Evaluation\\FragmentEvaluation';

   // Or, maybe better: only remove it for selected content elements:
   $GLOBALS['TCA']['tt_content']['types']['beautyofcode_contentrenderer']['columnsOverrides']['tx_content_slug_fragment']['config']['eval'] = 'trim,Sebkln\\ContentSlug\\Evaluation\\FragmentEvaluation';
   $GLOBALS['TCA']['tt_content']['types']['textpic']['columnsOverrides']['tx_content_slug_fragment']['config']['eval'] = 'trim,Sebkln\\ContentSlug\\Evaluation\\FragmentEvaluation';

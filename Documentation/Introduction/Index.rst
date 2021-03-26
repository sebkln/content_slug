.. include:: ../Includes.txt


.. _introduction:

Introduction
============

"Speaking URLs" are a must-have feature for web pages. TYPO3 v9 and newer provide the Routing feature for this.
Former TYPO3 versions needed the third-party extensions *RealURL* or *CoolUri*.

TYPO3 also provides the navigational content elements *"Section index"* and *"Section index of subpages from selected pages"*,
which will build a list of pages and their included content elements.

These content elements will be linked by their unique id, e.g.:

::

   https://www.example.org/a-sub-page/#c123

It's working well, but it's not human-readable.


.. _intro-what-it-does:

What does it do?
----------------

First of all, this extension provides **human-readable URL fragments** for TYPO3 content elements:

::

   https://www.example.org/a-sub-page/#section-of-interest

Furthermore, the extension allows to set **anchor links** next to the header.
An editor can activate these with a checkbox for individual content elements.

.. note::

   In fact, you can see both features in action on this very documentation page:

   - Hover your mouse over a heading. A link symbol will appear. **This is the anchor link.**
   - Click on this anchor. Your browser will jump to this section. **A readable fragment will be added to the URL.**


.. _intro-features:

Features
~~~~~~~~

- **Editors** can :ref:`set individual, human-readable fragment identifiers <editors-explanation-fragment>` per content element.
- **Editors** can use a button to automatically generate a fragment from the current header.
- **Editors** can :ref:`activate anchor links to headers <editors-explanation-anchorlink>` per content element.
- **Scope:** The human-readable fragment is used in *Section Index* menus, as well as for links in the RTE or TCA fields with renderType :php:`inputLink`.
- **Fallback:** If no custom fragment is given, or the header is hidden, the default fragment is used in rendered links.
- **Evaluation #1:** Only supported characters are stored. Special characters are replaced.
- **Evaluation #2:** All content elements on the same page will get a unique fragment identifier.
- :ref:`Error prevention <potential-issue>`: By default, the fragments are prepended with the uid of the content element.
- Of course, the Fluid templates can be customized to your needs.


.. _intro-screenshots:

Screenshots
-----------

You can find more screenshots in the Editors Manual.

.. figure:: ../Images/EditorManual/fields-in-content-element.png
   :width: 910px
   :alt: The new fields for editors
   :class: with-shadow

   The new fields for editors

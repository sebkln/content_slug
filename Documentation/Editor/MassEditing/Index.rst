.. include:: ../../Includes.txt


.. _editors-mass-editing:

=======================
Mass editing of anchors
=======================

The :guilabel:`List` module makes it possible to display the content of several
fields at once and gives you the ability to edit several records with one action.

This allows you, for example, to quickly add human-readable anchors to all content elements
on a page at once.

..  rst-class:: bignums-xxl

1. Click the :guilabel:`Show columns` button and enable the field "Human-readable URL #anchor".

   .. figure:: ../../Images/EditorManual/mass-editing-1.png
      :width: 910px
      :alt: Screenshot demonstrating the location of the "Show columns" Button in the TYPO3 List module
      :class: with-shadow

      A table with content elements in the :guilabel:`List` module. The anchor field is already enabled
      and visible as the last column the right.

2. Select the content elements you want to update, then click the :guilabel:`Edit columns` button

   Using the :guilabel:`Edit columns` button will open the header field together with every enabled column in the list.

   ..  note::
       In TYPO3 v12, this button does not exist yet. Open the *Single Table View* by clicking on the table's title
       "Page content ()" (see screenshot above) first, then select the content elements.
       The middle "Edit" button in the table will provide the same functionality.

   .. figure:: ../../Images/EditorManual/mass-editing-2.png
      :width: 910px
      :alt: Screenshot
      :class: with-shadow

      Selecting records for editing in TYPO3 v13 and newer

3. Edit the anchor fields of multiple content elements

   .. figure:: ../../Images/EditorManual/mass-editing-3.png
      :width: 910px
      :alt: Screenshot of the editing form that lists the selected fields
      :class: with-shadow

      Mass editing of anchors using the buttons on the right, with manual adjustments as needed

   ..  note::
       The buttons to generate an anchor from the header's contents will only work if the header field
       is visible in the editing form. Otherwise, you can only *manually* edit the human-readable anchor.

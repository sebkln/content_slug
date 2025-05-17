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

2. Access the *Single Table View*

   The *Single Table View* can be opened by clicking on the table's title "Page content ()" (see screenshot above).
   This view provides more "Edit" buttons than the default List view. These allow to edit multiple records and
   limit the editing form to certain fields.

3. Select the content elements you want to update, then click the middle "Edit" button

   The middle "Edit all shown fields of the listed records" button in the table will open the field "Header"
   along with the enabled fields.

   .. figure:: ../../Images/EditorManual/mass-editing-2.png
      :width: 910px
      :alt: Screenshot
      :class: with-shadow

      Selecting records for editing in TYPO3 v11

4. Edit the anchor fields of multiple content elements

   .. figure:: ../../Images/EditorManual/mass-editing-3.png
      :width: 910px
      :alt: Screenshot of the editing form that lists the selected fields
      :class: with-shadow

      Mass editing of anchors using the buttons on the right, with manual adjustments as needed

   ..  note::
       The buttons to generate an anchor from the header's contents will only work if the header field
       is visible in the editing form. Otherwise, you can only *manually* edit the human-readable anchor.

.. include:: ../../Includes.txt


.. _editors-preface:

=======
Preface
=======

URL anchors (or *fragments*) are a handy feature for directly navigating
a specific section of a web page.

This is also very useful if you want to share a particular section of a page
with another person.

TYPO3 provides several ways to link to content elements.
The generated URL in your web browser will look something like this:

::

   https://www.your-website.com/a-sub-page/#c123

It would be nice if ``#c123`` were more meaningful:

::

   https://www.your-website.com/a-sub-page/#c123-some-interesting-content

This extension will help you with that!


.. _editors-fragment-scope:

Where will my URL anchor be used?
=================================

There are several ways in TYPO3 to link directly to a content element:

.. rst-class:: bignums

1. Content elements *"Section index"* and
   *"Section index of subpages from selected pages"*.

   These menus will automatically provide a **list of links** to
   content elements on (selected) subpages.

2. Rich text editor (RTE)

   You can set individual links to content elements in the RTE through
   the Link Browser.

3. Link fields

   You can e.g. add a link to the content element's header.

Whenever the subsequent requirements are fulfilled,
the human-readable URL anchor will be used in these links.


.. _editors-fragment-conditions:

When will my URL anchor be used?
================================

TYPO3 will use your human-readable URL anchor if the following
requirements are met:

1. Of course, the backend field for the URL anchor must be filled.
2. Your header must be visible in the frontend (not set to "Hidden").

Otherwise, links to this content element will still use the default,
which is the element's unique id.

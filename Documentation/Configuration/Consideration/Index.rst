.. include:: ../../Includes.txt


.. _consideration:

Considerations
==============

By default, this extension will generate the following HTML markup for
TYPO3 content element headers:

.. code-block:: html

   <header>
       <h1 id="c35-section-of-interest" class="">
           This is the header of an interesting part of my article

           <!-- This link is only rendered if 'Set link to #anchor' is activated -->
           <a class="headline-anchor" href="#c35-section-of-interest">#</a>
       </h1>
   </header>

As you can see, the fragment identifier is used as an ``id`` attribute in HTML.
This ``id`` can then be referenced in a link.


.. _potential-issue:

Potential issue
---------------

.. warning::

   A URL fragment could accidentally match the ``id`` of an element in the
   website's HTML template (e.g. "#main-navigation").
   This template-related identifier could be styled with CSS, or be accessed with
   JavaScript. Most likely, this would end in unexpected side effects.

By adding the uid of the current TYPO3 content element, we'll circumvent this
potential issue.

.. tip::

   You can **remove or adapt the prefix** of the fragment identifier in the
   Fluid templates.


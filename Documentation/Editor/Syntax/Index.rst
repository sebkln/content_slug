.. include:: ../../Includes.txt


.. _editors-fragment-syntax:

==============================
Correct syntax for URL anchors
==============================

Please be aware that only **a limited set of characters** can be used for
such an URL anchor (officially called a "fragment identifier").
These are technical limitations.

Also, **all URL anchors on the same page must be unique!** If you use the
same URL anchor in two or more content elements on the same page, they will
be automatically appended with increasing numbering when saving.

If you write a non-supported character in the URL anchor field, it will be
**replaced** when you leave the field or save the content element.

.. tip::

   For your convenience, you can e.g. fill the URL anchor field with
   *"Learn all about Product X"*.

   When you leave the field or save the content element, it will be converted to
   *"learn-all-about-product-x"*.

**The following characters are allowed in this field:**

- ASCII characters (``a–z``)
- digits (``0–9``)
- underscores (``_``)
- hyphens (``-``)
- periods (``.``)

**As soon as you leave the field (or the content element is saved) …**

- … all characters are converted to lowercase.
- … HTML elements are removed completely.
- … space characters are converted to the hyphen character.
- … special characters (e.g. ``äöüß€``) are converted to ASCII equivalents.

.. tip::

   **Readability:** The URL anchor doesn't have to match your header exactly.
   But it will be more understandable for your website's visitor if it
   reflects the content of this text section.

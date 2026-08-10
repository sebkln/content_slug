.. _admin-upgrade:

=======================
Upgrading the extension
=======================

.. _admin-upgrade-to-v6:

Upgrading to EXT:content_slug to 6.x
====================================

.. warning::
   With version 6.0.0, the TypoScript configuration is split into three sets.

   **This is a breaking change in any case!**

This change lets you use the extension with various templating solutions, like Content Blocks or Bootstrap Package.

If you upgrade, you will need to adjust the import of the Site Set or the TypoScript set (`include_static_file` in TypoScript records).

* The *Site set* with identifier `sebkln/content-slug` **no longer exists**.
* The *TypoScript set* "Speaking URL fragments (anchors)" was renamed to "Speaking URL fragments (Basic configuration)".
  **It no longer contains the template overrides for Fluid Styled Content.**
  As the file path remains unchanged, the set will continue to load.

When upgrading this extension, include one of the three new Site sets or TypoScript sets.
The page :ref:`admin-sets` contains an overview and recommendations based on your type of content elements.


.. _admin-upgrade-to-v2:

Upgrading from EXT:content_slug 1.x to 2.x
==========================================

Version 2.0.0 allows to configure the URL fragment with TypoScript. Depending on
how you customized Fluid templates, this *can* be a breaking change.

We can differentiate between the two *Header* partials and the *Section Index*
templates:

Potentially breaking: Header partials
-------------------------------------

Formerly, the *Header/All.html* partial transferred two additional variables to
the *Header/Header.html* partial:

.. code-block:: html

   uid: data.uid,
   fragmentIdentifier: data.tx_content_slug_fragment,

This was then used in the *Header/Header.html* partial to build the complete
URL fragment:

.. code-block:: html

   <h1 id="{f:if(condition: fragmentIdentifier, then: 'c{uid}-{fragmentIdentifier}')}" class="{positionClass}">
       <f:link.typolink parameter="{link}">{header}</f:link.typolink>
       <f:if condition="{fragmentIdentifier} && {renderAnchorLink}"><a class="headline-anchor" href="#c{uid}-{fragmentIdentifier}">#</a></f:if>
   </h1>

**This has changed.** ``fragmentIdentifier`` is still used as the variable name
in the *Header/Header.html* partial. But instead of the raw contents of the
``tx_content_slug_fragment`` field, it now contains the complete URL fragment
:ref:`configured with TypoScript <configuration-typoscript>`:

.. code-block:: html

   fragmentIdentifier: fragmentIdentifier,

This should *only* be a breaking change if you use **one** of the Header
partials directly from this extension **and** customized the other one in your
sitepackage.

Should be non-breaking: "Section Index" templates
-------------------------------------------------

Both *"Section Index"* templates were simplified, using the new TypoScript
variable ``fragmentIdentifier``.

**Old version:**

.. code-block:: html

   <a href="{page.link}#{f:if(condition: '({element.data.tx_content_slug_fragment} && {element.data.header_layout} != 100)', then: 'c{element.data.uid}-{element.data.tx_content_slug_fragment}', else: 'c{element.data.uid}')}"

**New version:**

.. code-block:: html

   <a href="{page.link}#{f:if(condition: '{element.fragmentIdentifier}', then: '{element.fragmentIdentifier}', else: 'c{element.data.uid}')}"

The default configuration provides the same URL fragment as before
(a combination of UID and human-readable fragment).

If you customized these templates in your sitepackage, this should not be
a breaking change – the ``tx_content_slug_fragment`` database field is still
available, after all. And if you adjusted the prefix, this will remain the same.

.. attention::
   Nonetheless, I advise that you update **all** templates with the new
   TypoScript variable. This ensures that the URL fragment is configured at a
   central place.

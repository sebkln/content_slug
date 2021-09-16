.. include:: ../../Includes.txt


.. _templating:

==========
Templating
==========

This extension enhances the existing TYPO3 content elements, which are commonly
rendered with `EXT:fluid_styled_content`. Therefore, customized Fluid templates
have to be provided by this extension.

It also works well with the Bootstrap Package.

In both cases, be sure to load the configuration of this extension **after**
`EXT:fluid_styled_content` or `EXT:bootstrap_package`.


.. _templating-ts:

TypoScript setup
================

You can configure the fragment identifier with :ref:`TypoScript <configuration-typoscript>`.

If you customize the templates, override the template paths of the content elements.

.. code-block:: typoscript

   lib.contentElement {
       partialRootPaths.101 = EXT:content_slug/Resources/Private/Overrides/fluid_styled_content/Partials/
       templateRootPaths.101 = EXT:content_slug/Resources/Private/Overrides/fluid_styled_content/Templates/
   }


.. _templating-fluid:

Fluid templates
================

The customized Fluid templates contain some new variables and ViewHelpers to
render the contents of the new fields.


Resources/Private/Overrides/fluid_styled_content/Partials/Header/All.html
-------------------------------------------------------------------------

We need to **transfer additional variables** to *Header.html*:

.. code-block:: html
   :linenos:
   :emphasize-lines: 6-7

   <f:render partial="Header/Header" arguments="{
       header: data.header,
       layout: data.header_layout,
       positionClass: '{f:if(condition: data.header_position, then: \'ce-headline-{data.header_position}\')}',
       link: data.header_link,
       fragmentIdentifier: fragmentIdentifier,
       renderAnchorLink: data.tx_content_slug_link,
       default: settings.defaultHeaderType}" />


Resources/Private/Overrides/fluid_styled_content/Partials/Header/Header.html
----------------------------------------------------------------------------

Each heading (``<h1>`` to ``<h5>``) gets a new ``id`` attribute. It will contain
the configured **fragment identifier**, if a fragment was set in the
content element.

If a fragment exists and the checkbox "Set link to #anchor" is activated,
an additional link will be rendered right to the header.

You can style this anchor through the class name and/or change the displayed
symbol in the template.

.. code-block:: html
   :linenos:
   :emphasize-lines: 1,3

   <h1 id="{fragmentIdentifier}" class="{positionClass}">
       <f:link.typolink parameter="{link}">{header}</f:link.typolink>
       <f:if condition="{fragmentIdentifier} && {renderAnchorLink}"><a class="headline-anchor" href="#{fragmentIdentifier}">#</a></f:if>
   </h1>

.. important::

   Note the spelling: **with** ``#`` in the anchor link, **without** ``#`` in
   the ``id`` attribute!

Also important: if ``header_layout`` is set to *default*, the *Header.html*
partial is called a second time. Therefore, we need to transfer our additional
variables again:

.. code-block:: html
   :linenos:
   :emphasize-lines: 7-8

   <f:defaultCase>
       <f:if condition="{default}">
           <f:render partial="Header/Header" arguments="{
               header: header,
               layout: default,
               positionClass: positionClass,
               fragmentIdentifier: fragmentIdentifier,
               renderAnchorLink: renderAnchorLink,
               link: link}"/>
       </f:if>
   </f:defaultCase>


Resources/Private/Overrides/fluid_styled_content/Templates/MenuSection.html
---------------------------------------------------------------------------

The TYPO3 content elements of ``CType`` *"Section Index"* and
*"Section Index of subpages from selected pages"* both build a list of pages and
their included content elements.

By default, the content elements will be linked by their unique id, e.g.
`https://www.example.org/a-sub-page/#c123`.

The new Fluid condition will check if a fragment identifier is given for the
content element.

.. note::
   The human-readable fragment can only rendered if the header is **not hidden**.
   Therefore, we also need to check if the ``header_layout`` is set to ``100``.

   This is taken care of in the :ref:`custom DataProcessor <FragmentIdentifierProcessor>`,
   which is added to both *Section Index* menus.

If available, the configured fragment identifier is then rendered
(identical to the anchor link in *Header.html*).

Otherwise, the default anchor to the content element is rendered (``#c123``).

.. code-block:: html
   :linenos:
   :emphasize-lines: 4

   <f:for each="{page.content}" as="element">
       <f:if condition="{element.data.header}">
       <li>
           <a href="{page.link}#{f:if(condition: '{element.fragmentIdentifier}', then: '{element.fragmentIdentifier}', else: 'c{element.data.uid}')}"
               {f:if(condition: page.target, then: ' target="{page.target}"')} title="{element.data.header}">
               <span>{element.data.header}</span>
           </a>
       </li>
       </f:if>
   </f:for>

.. note::

   The same changes apply in *MenuSectionPages.html*.

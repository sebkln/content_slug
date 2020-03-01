.. include:: ../../Includes.txt


.. _templating:

Templating
==========

This extension enhances the existing TYPO3 content elements, which are commonly
rendered with *EXT:fluid_styled_content*. Therefore, customized Fluid templates
have to be provided by this extension.


.. _templating-ts:

TypoScript setup
----------------

The extension currently only sets new template paths for
*EXT:fluid_styled_content*.

.. code-block:: typoscript

   lib.contentElement {
       partialRootPaths.101 = EXT:content_slug/Resources/Private/Overrides/fluid_styled_content/Partials/
       templateRootPaths.101 = EXT:content_slug/Resources/Private/Overrides/fluid_styled_content/Templates/
   }


.. _templating-fluid:

Fluid templates
---------------

The customized Fluid templates contain some new variables and viewhelpers to
render the contents of the new fields.


Resources/Private/Overrides/fluid_styled_content/Partials/Header/All.html
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

We need to **transfer additional variables** to *Header.html*:

.. code-block:: html
   :linenos:
   :emphasize-lines: 6-8

   <f:render partial="Header/Header" arguments="{
       header: data.header,
       layout: data.header_layout,
       positionClass: '{f:if(condition: data.header_position, then: \'ce-headline-{data.header_position}\')}',
       link: data.header_link,
       uid: data.uid,
       fragmentIdentifier: data.tx_content_slug_fragment,
       renderAnchorLink: data.tx_content_slug_link,
       default: settings.defaultHeaderType}" />


Resources/Private/Overrides/fluid_styled_content/Partials/Header/Header.html
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Each heading (``<h1>`` to ``<h5>``) gets a new ``id`` attribute. It will contain
the **uid** and **fragment identifier**, if a fragment was set in the
content element.

If a fragment exists and the checkbox "Set link to #anchor" is activated,
an additional link will be rendered right to the header.

You can style this anchor through the class name and/or change the displayed
symbol in the template.

.. code-block:: html
   :linenos:
   :emphasize-lines: 1,3

   <h1 id="{f:if(condition: fragmentIdentifier, then: 'c{uid}-{fragmentIdentifier}')}" class="{positionClass}">
       <f:link.typolink parameter="{link}">{header}</f:link.typolink>
       <f:if condition="{fragmentIdentifier} && {renderAnchorLink}"><a class="headline-anchor" href="#c{uid}-{fragmentIdentifier}">#</a></f:if>
   </h1>

.. important::

   Note the spelling: **with** ``#`` in the anchor link, **without** ``#`` in
   the ``id`` attribute!

Also important: if ``header_layout`` is set to *default*, the *Header.html*
partial is called a second time. Therefore, we need to transfer our additional
variables again:

.. code-block:: html
   :linenos:
   :emphasize-lines: 7-9

   <f:defaultCase>
       <f:if condition="{default}">
           <f:render partial="Header/Header" arguments="{
               header: header,
               layout: default,
               positionClass: positionClass,
               uid: uid,
               fragmentIdentifier: fragmentIdentifier,
               renderAnchorLink: renderAnchorLink,
               link: link}"/>
       </f:if>
   </f:defaultCase>


Resources/Private/Overrides/fluid_styled_content/Templates/MenuSection.html
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The TYPO3 content elements of ``CType`` *"Section menu"* and
*"Section index of subpages from selected pages"* both build a list of pages and
their included content elements.

By default, the content elements will be linked by their unique id, e.g.
`https://www.example.org/a-sub-page/#c123`.

The new Fluid condition will check for two things:

#. Is a **fragment available** in the content element?
#. Is the **header not hidden** (``header_layout`` != 100)?

If both conditions are true, a combination of uid and fragment is rendered
(identical to the anchor link in *Header.html*).

Otherwise, the default anchor to the content element is rendered (``#c123``).

.. code-block:: html
   :linenos:
   :emphasize-lines: 4

   <f:for each="{page.content}" as="element">
       <f:if condition="{element.data.header}">
       <li>
           <a href="{page.link}#{f:if(condition: '({element.data.tx_content_slug_fragment} && {element.data.header_layout} != 100)', then: 'c{element.data.uid}-{element.data.tx_content_slug_fragment}', else: 'c{element.data.uid}')}"
              {f:if(condition: page.target, then: ' target="{page.target}"')} title="{element.data.header}">
               <span>{element.data.header}</span>
           </a>
       </li>
       </f:if>
   </f:for>

.. note::

   The same changes apply in *MenuSectionPages.html*.

.. _admin-sets:

==============
Choosing a set
==============

The extension provides three Site sets and TypoScript Sets.

**All sets include:**

* TypoScript configuration for the human-readable fragment
* Configurable via Site settings or TypoScript constants

.. rst-class:: bignums

1. Speaking URL fragments (Basic configuration)

   Contains no template overrides.

   **Recommended for:**

   * :t3ext:`EXT:bootstrap_package`
   * :t3ext:`EXT:theme_camino`
   * Any custom content elements that are not based on Fluid Styled Content or Content Blocks

2. Speaking URL fragments (for Fluid Styled Content)

   Includes template overrides for the header partials
   and the *Section Index* menu content elements.

   **Recommended for:**

   * :t3ext:`EXT:fluid_styled_content`

3. Speaking URL fragments (for Content Blocks)

   Contains no template overrides.

   The extension provides an example of how to add the fragment-related backend fields
   to your content blocks in this file:
   :path:`ContentBlocks/Basics/ContentElements/Header.yaml`

   **Recommended for:**

   * Custom content elements created with :t3ext:`EXT:content_blocks`

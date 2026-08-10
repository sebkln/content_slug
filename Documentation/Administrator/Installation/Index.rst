.. _admin-installation:

============
Installation
============

The extension needs to be installed as any other extension of TYPO3 CMS.

Perform the following steps:

.. rst-class:: bignums-xxl

1. Get the extension

   #. **Use composer**: Use ``composer require sebkln/content-slug``

   #. **Use the Extension Manager:** Select "Get extensions". Press the
      "Update now" button and search for the extension key **content_slug**.
      Download the latest version by using the *Import* button, or click on the
      extension's title to download a version of your choice.

   #. **Get it from typo3.org:** You can download a version's ZIP archive from
      `https://extensions.typo3.org/extension/content_slug
      <https://extensions.typo3.org/extension/content_slug>`_.
      Afterwards, upload the file in the Extension Manager.

   You can also get the latest *dev-master* version from `GitHub
   <https://github.com/sebkln/content_slug>`_ by using the command line:

   .. code-block:: bash

      git clone https://github.com/sebkln/content_slug.git

2. Install the extension

   Activate the extension in the TYPO3 backend module
   **Admin Tools > Extensions**.

   If you are using TYPO3 11.4 (or later) **and** composer, all extensions are
   automatically considered as active.

3. Include one of the Site sets or TypoScript sets

   You can choose from three available sets.

   See :ref:`admin-sets` for a recommendation based on your type of content elements.

4. Customize configuration and templates

   This extension adds the :typoscript:`fragmentIdentifier` variable to the Fluid templates
   of content elements.

   For EXT:fluid_styled_content, ready to use templates are already provided.

   If you use a different base for your content elements
   (e.g. EXT:content_blocks or EXT:bootstrap_package),
   you will need to extend the Fluid templates yourself.

   The :ref:`configuration` section covers TypoScript settings and templating.
   Be sure to read :ref:`consideration` first.

5. Optional: Configure field permissions (for your editors)

   If your website uses Backend usergroups to configure access rights to
   fields, check out the :ref:`user-permissions`.

.. include:: ../../Includes.txt


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

   If you're using TYPO3 11.4 (or later) **and** composer, all extensions are
   automatically considered as active.

3. Include the static template

   The extension ships some TypoScript code which needs to be included.

   #. Switch to the root page of your website.
   #. Open the *Template* record.
   #. Switch to the **Includes** tab of the template record.
   #. Select **"Speaking URL fragments (anchors) (content_slug)"** in the field
      *Include static (from extensions)*. It must be loaded **after** the static
      template *"Fluid Content Elements (fluid_styled_content)"*!

   .. figure:: ../../Images/AdministratorManual/include-static-template.png
      :width: 854px
      :alt: Include the static template
      :class: with-shadow

      Include the static template

4. Customize configuration and templates

   This extension extends EXT:fluid_styled_content and therefore provides
   customized Fluid templates.

   .. important::

      If you already customized the same Fluid templates for your website,
      you'll need to extend your version with some new variables and ViewHelpers.

   The :ref:`configuration` section covers TypoScript settings and templating.
   Be sure to read :ref:`consideration` first.

5. Optional: Configure field permissions (for your editors)

   If your website uses Backend usergroups to configure access rights to
   fields, check out the :ref:`user-permissions`.

.. include:: ../Includes.txt


.. _known-problems:

Known Problems
==============

.. note::
   If you encounter an error, please report it `here <https://github.com/sebkln/content_slug/issues>`__.

Current limitation in :typoscript:`FragmentIdentifierProcessor`
---------------------------------------------------------------

While the DataProcessor can process TypoScript references (:typoscript:`=<`)
to other cObjects **inside** :typoscript:`fragmentIdentifier`,
:typoscript:`lib.contentElement.variables.fragmentIdentifier` mustn't be a
reference by itself.

.. code-block:: typoscript

   // This would work:
   lib.contentElement {
       variables.fragmentIdentifier < lib.yourCustomFragment
   }

   // This would work:
   lib.contentElement {
       variables.fragmentIdentifier = COA
       variables.fragmentIdentifier {
           10 =< lib.yourCustomFragment
       }
   }

   // This won't work:
   lib.contentElement {
       variables.fragmentIdentifier =< lib.yourCustomFragment
   }

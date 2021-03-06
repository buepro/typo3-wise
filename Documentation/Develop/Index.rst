.. include:: /Includes.rst.txt

.. _develop:

=======
Develop
=======

This chapter is of interest when developing this extension further.

.. _develop-site:

Site
====

-  Create `Build/site/wise/private.yaml` with wise related properties
   (see :ref:`Administration - Site configuration <admin>`)
-  Create the key files in `Build/site/wise`
   (see :ref:`Administration - Keys files <admin>`)

.. _develop-logging:

Logging
=======

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['LOG']['Buepro']['Wise']['writerConfiguration'] = [
       \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
           \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
               'logFile' => \TYPO3\CMS\Core\Core\Environment::getVarPath() . '/log/wise.log'
           ],
       ],
   ];

.. _develop-various:

Various
=======

-  Expose dev server: `ddev share`

.. _develop-references:

References
==========

Wise API References
-------------------

-  `PHP Example API <https://github.com/robclark56/TransferWise_PHP_SimpleAPIclass>`__
-  `PHP Example Webhook <https://github.com/robclark56/TransferWise_PHP_webhook>`__
-  `API Postman collection <https://github.com/transferwise/public-api-postman-collection>`__
-  `Balance account statement <https://api-docs.transferwise.com/#balance-account-get-balance-account-statement>`__

PHP References
--------------

-  `Start new thread <https://stackoverflow.com/questions/8548419/most-simple-way-to-start-a-new-process-thread-in-php#8548484>`__

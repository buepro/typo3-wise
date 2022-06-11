.. include:: /Includes.rst.txt

.. _admin:

==============
Administration
==============

.. _admin_keys:

Public and private keys
=======================

Generate the key files and store them to `config/site/[identifier]/wise/`.
The following linux command could be used to generate the key files:

.. code-block:: shell

   openssl genrsa -out private.pem 2048
   openssl rsa -pubout -in private.pem -out public.pem

.. _admin_site_configuration:

Site configuration
==================

#. Define an url segment challenge for wise events in the site configuration
   for the property `wise.eventUrlSegmentChallenge`. For security reasons prefer
   something cryptic like `wdoufkyrkLoqaarxxvmdxyyj`.

#. Define where to store event and credit transaction records by setting the
   property `wise.storageUid` in the site configuration. The property can
   contain a single uid or a coma separated list of uid's. In the later case
   the first uid will be used to store new records.

#. Create a read only API token in your wise account (see below) and assign its
   key to the property `wise.apiTokenKey`.

#. Optionally define the directory where the typo3 command is located with the
   property `wise.binDirectory`

#. Optionally define a post event command to be executed in a background
   process by assigning it to the property `wise.postEventCommand`. The post
   event command overwrites the automatic created command hence as well the
   property `wise.binDirectory` has no effect any more.


.. code-block:: yaml
   :caption: Example configuration from REQUIRED wise properties

   wise:
     eventUrlSegmentChallenge: wdoufkyrkLoqaarxxvmdxyyj
     storageUid: 1
     apiTokenKey: '11111111-1111-1111-1111-111111111111'

.. code-block:: yaml
   :caption: Example configuration from OPTIONAL wise properties

   wise:
     binDirectory: ../vendor/bin
     postEventCommand: "/path/to/php -f '../path/to/bin/typo3' -- 'wise:getcredits' > /dev/null 2>&1 &"

.. note::

   In case new credit records aren't created upon receiving an event there
   might be two reasons for it:

   -  Wise delayed the availability from the credit transaction -> the
      The transaction might be pulled in after five minutes.

   -  The command used to get the credit transactions didn't work. To further
      find out details about it the command details can be obtained by
      activation the debug logging (see :ref:`develop-logging`).

.. _admin_wise_account:

Wise account
============

#. Register the event handler with a webhook. The webhook properties are:

   -  Subscription event: Balance deposits
   -  URL: `[https://domain.ch]/wise-event-handler-[url segment challenge]`

   .. hint::

      Replace `[url segment challenge]` with the value previously assigned to
      the site configuration property `wise.eventUrlSegmentChallenge`.

#. Create an API token with "read only" permissions and assign its key to the
   site configuration property `wise.apiTokenKey`.

#. Add the public key from `config/site/[identifier]/wise/public.pem` to the
   API tokens.

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

#. Optionally define the directory where the typo3 command is located in the
   property `wise.binDirectory`


.. code-block:: yaml
   :caption: Example wise properties in site configuration

   wise:
     eventUrlSegmentChallenge: wdoufkyrkLoqaarxxvmdxyyj
     storageUid: 1
     apiTokenKey: '11111111-1111-1111-1111-111111111111'
     binDirectory: ../vendor/bin

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

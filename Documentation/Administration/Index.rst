.. include:: /Includes.rst.txt

.. _admin:

==============
Administration
==============

Follow these steps to authenticate the app, use the
:ref:`console commands <user-manual-console>` and listen to
:ref:`events <api-events>`.

----

.. rst-class:: bignums-xxl

#. Url segment challenge

   Create a **url segment challenge** consisting of lower case letters and
   numbers with about 20 characters. The value will be used in the site
   configuration (property `eventUrlSegmentChallenge`) and the wise web site.

#. Record storage

   Create a folder to hold the event and credit transaction record. The folder
   uid will be assigned to the site property `storageUid`.

#. Site configuration

   -  Create a wise folder in your site directory. In case the site identifier
      is `default` the directory hierarchy would be `config/sites/default/wise`.

   -  Create a wise configuration file `config/sites/default/wise/site.yaml`.

   -  Include the wise configuration file in `config/sites/default/config.yaml`
      by adding the following lines on the bottom:

      .. code-block:: yaml

         imports:
           - { resource: './wise/site.yaml' }

   -  Copy the following configuration into the wise configuration file
      (`config/sites/default/wise/site.yaml`) and set the
      `eventUrlSegmentChallenge` and the `storageUid`. The `apiTokenKey` will
      be set later.

      .. code-block:: yaml

         wise:
           apiTokenKey: '11111111-1111-1111-1111-111111111111'
           eventUrlSegmentChallenge: wdoufkyrkLoqaarxxvmdxyyj
           storageUid: 1

#. Key files

   Generate the key files and store them to `config/site/[identifier]/wise/`.
   The following linux command could be used to generate the key files:

   .. code-block:: shell

      openssl genrsa -out private.pem 2048
      openssl rsa -pubout -in private.pem -out public.pem

#. App authentication

   -  Login to your wise account and create an API token with "read only"
      permissions and assign its key to the site configuration property
      `wise.apiTokenKey`.

   -  Add the public key from `config/site/[identifier]/wise/public.pem` to the
      API tokens.

#. Balance deposit hook

   Register the event handler with a webhook. The webhook properties are:

   -  Subscription event: Balance deposits
   -  URL: `[https://domain.ch]/wise-event-handler-[url segment challenge]`

   .. hint::

      Replace `[url segment challenge]` with the value previously assigned to
      the site configuration property `wise.eventUrlSegmentChallenge`.

#. Use it...

   Use the :ref:`console commands <user-manual-console>`, listen to
   :ref:`events <api-events>` and create a test by transferring 0.1€ to your
   wise account.

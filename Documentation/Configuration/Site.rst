.. include:: /Includes.rst.txt

.. _config-site:

==================
Site configuration
==================

.. note::

   For changes made in the site configuration to take effect the cache has to
   be cleared.

.. code-block:: yaml
   :caption: Example configuration (commented properties are optional)

   wise:
     apiTokenKey: '11111111-1111-1111-1111-111111111111'
     eventUrlSegmentChallenge: wdoufkyrkLoqaarxxvmdxyyj
     storageUid: 1
     # binDirectory: ../vendor/bin
     # postEventCommand: "/path/to/php -f '../path/to/bin/typo3' -- 'wise:getcredits' > /dev/null 2>&1 &"

.. _config-site-required:

Required properties
===================

.. code-block:: yaml
   :caption: Required site properties

   wise:
     apiTokenKey: '11111111-1111-1111-1111-111111111111'
     eventUrlSegmentChallenge: wdoufkyrkLoqaarxxvmdxyyj
     storageUid: 1

.. index:: Site config; apiTokenKey
.. _config-site-apiTokenKey:

apiTokenKey
-----------

.. container:: table-row

   Property
      wise.apiTokenKey

   Data type
      string

   Description
      The API token provided by your wise account.

.. index:: Site config; eventUrlSegmentChallenge
.. _config-site-eventUrlSegmentChallenge:

eventUrlSegmentChallenge
------------------------

.. container:: table-row

   Property
      wise.eventUrlSegmentChallenge

   Data type
      string

   Description
      URL segment that will be used in the event handler url. For security
      reasons prefer something cryptic like `wdoufkyrkLoqaarxxvmdxyyj`.

.. index:: Site config; storageUid
.. _config-site-storageUid:

storageUid
----------

.. container:: table-row

   Property
      wise.storageUid

   Data type
      int/string

   Description
      Defines where to store event and credit transaction records. The property
      can contain a single uid or a coma separated list of uid's. In the later
      case the first uid will be used to store new records.

.. _config-site-optional:

Optional properties
===================

.. code-block:: yaml
   :caption: Optional site properties

   wise:
     binDirectory: ../vendor/bin
     postEventCommand: "/path/to/php -f '../path/to/bin/typo3' -- 'wise:getcredits' > /dev/null 2>&1 &"

.. index:: Site config; binDirectory
.. _config-site-binDirectory:

binDirectory
------------

.. container:: table-row

   Property
      wise.binDirectory

   Data type
      string

   Description
      Defines the directory where the typo3 command is located. Without this
      property the command is obtained by the environment settings.

.. index:: Site config; postEventCommand
.. _config-site-postEventCommand:

postEventCommand
----------------

.. container:: table-row

   Property
      wise.postEventCommand

   Data type
      string

   Description
      Overwrites the automatically created post event command being executed as
      a background process, hence as well the property `wise.binDirectory` has
      no effect any more.

.. note::

   In case new credit records aren't created upon receiving an event from wise
   there might be two reasons for it:

   -  Wise delayed the availability from the credit transaction -> the
      The transaction might be pulled in after five minutes.

   -  The command used to get the credit transactions didn't work. To further
      find out details about it the command details can be obtained by
      activation the debug logging (see :ref:`develop-logging`).

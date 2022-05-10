.. include:: /Includes.rst.txt

.. _user_manual:

===========
User manual
===========

Console
=======

.. code-block:: shell
   :caption: Get help

   path/to/bin/typo3 wise:getcredits -h

.. code-block:: shell
   :caption: Get credit transactions one month back and for all sites

   path/to/bin/typo3 wise:getcredits

.. code-block:: shell
   :caption: Get credit transactions only for the site with identifier "default"

   path/to/bin/typo3 wise:getcredits -s default

.. code-block:: shell
   :caption: Get credit transactions only for the profile id 1234567

   path/to/bin/typo3 wise:getcredits -p 1234567

.. code-block:: shell
   :caption: Get credit transactions for the period 06.05.2022 - 06.06.2022

   path/to/bin/typo3 wise:getcredits -f 06.05.2022

.. code-block:: shell
   :caption: Get credit transactions for the period 06.05.2022 - 06.06.2022

   path/to/bin/typo3 wise:getcredits -t 06.06.2022

.. code-block:: shell
   :caption: Get credit transactions for the period 06.05.2022 - 10.05.2022

   path/to/bin/typo3 wise:getcredits -f 06.05.2022 -t 10.05.2022

.. note::

   The php function `strtotime` is used to get the timestamp from a date.

.. note::

   The profile id is looked up on registered events. In case there are no event
   records it needs to be provided with the `-p` option.

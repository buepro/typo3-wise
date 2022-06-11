.. include:: /Includes.rst.txt

.. _api:

===
API
===

.. _api-events:

Events
======

.. index:: API - Events; AfterAddingCreditsEvent
.. _api-events-AfterAddingCreditsEvent:

AfterAddingCreditsEvent
-----------------------

:php:`\Buepro\Wise\Event\AfterAddingCreditsEvent`

The event is dispatched after new credit records have been added.

:getAddedCredits(): Returns an array of :php:`\Buepro\Wise\Domain\Model\Credit`.

.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _introduction:

Introduction
============

.. contents::


.. _what-it-does:

What does it do?
----------------

A TYPO3 extension to send any pages as a newsletter to several recipients at 
once.

Originally based on `TC Directmail`_ 2.0.2,
the mailing engine was almost entirely rewritten but most features were 
preserved.We now use SwiftMailer (from TYPO3 core). And it aims to improve the 
user experience and works out of the box.

.. _TC Directmail: http://typo3.org/extensions/repository/view/tcdirectmail/current/

.. _how-it-compares:

Comparison with TC Directmail 2.0.2
-----------------------------------

What's better
^^^^^^^^^^^^^

-  Use of `SwiftMailer`_
-  Brand new database structure allowing for much more size efficient
   storage
-  Two special markers available: :code:`###newsletter_view_url###` and
   :code:`###newsletter_unsubscribe_url###`
-  Better cleaning of javascript in email content
-  Plain text quality is improved
-  When using spies on links, Link targets are included in the spied
   link, so the final target can always be reached even when database
   content is truncated or down

What's worse
^^^^^^^^^^^^

-  Removed wizard for recipientlist generation
-  Only one recipient list per newsletter (workaround: send multiple
   newsletter or UNION via raw sql)
-  Removed load-balancing to send email (see https://github.com/Ecodev/newsletter/issues/4)

.. _SwiftMailer: http://swiftmailer.org/

.. _screenshots:

Screenshots
-----------

Current status of newsletter
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. figure:: ../Images/UserManual/Newsletter_-_Status.png
   :width: 800px
   :alt: Status of Newsletter

Settings for newsletter
^^^^^^^^^^^^^^^^^^^^^^^

.. figure:: ../Images/UserManual/Newsletter_-_Settings.png
   :width: 800px
   :alt: Settings for Newsletter

Newsletter planning (and testing)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. figure:: ../Images/UserManual/Newsletter_-_Sending.png
   :width: 800px
   :alt: Newsletter planning and testing

Statistics overview with charts for one newsletter
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. figure:: ../Images/UserManual/Statistics_-_Overview.png
   :width: 800px
   :alt: Statistics overview for one newsletter

Statistics of all emails for one newsletter
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. figure:: Images/UserManual/Statistics_-_Emails.png
   :width: 800px
   :alt: Statistics of all emails for one newsletter

Statistics of all links for one newsletter
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. figure:: ../Images/UserManual/Statistics_-_Links.png
   :width: 800px
   :alt: Statistics of all links for one newsletter

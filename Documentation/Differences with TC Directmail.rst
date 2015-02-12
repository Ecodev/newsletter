Differences with TC Directmail
==============================

What's better
-------------

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
------------

-  Removed wizard for recipientlist generation
-  Only one recipient list per newsletter (workaround: send multiple
   newsletter or UNION via raw sql)
-  Removed load-balancing to send email (see #27046)

Breaking changes
----------------

We tried to keep most things from TC Directmail working as before, but
we still had to drop/change stuff for simplicity sake.

-  The hook ``getConfiguredMailerHook()`` now has a
   ``\Ecodev\Newsletter\Domain\Model\Newsletter`` object instead of page
-  Recipient do not have their own UID, instead we use email address.
   This affect ``RecipientList::disableReceiver()``,
   ``RecipientList::registerOpen()``, ``RecipientList::registerClick()``
   and their inherited versions.

.. _SwiftMailer: http://swiftmailer.org/

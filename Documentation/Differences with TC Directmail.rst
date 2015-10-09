Differences with TC Directmail 2.0.2
====================================

What's better
-------------

-  Use of `SwiftMailer`_
-  Brand new database structure allowing for much more size efficient
   storage
-  Two special markers available: :code:`###newsletter_view_url###` and
   :code:`###newsletter_unsubscribe_url###`
-  Better cleaning of javascript in email content
-  Plain text quality is improved

What's worse
------------

-  Removed wizard for recipientlist generation
-  Only one recipient list per newsletter (workaround: send multiple
   newsletter or UNION via raw sql)

.. _SwiftMailer: http://swiftmailer.org/

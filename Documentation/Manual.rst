.. contents :: :local:


Getting Started
===============

Basic extension configuration
-----------------------------

#. If not done already, set up TYPO3 Sheduler. See `Scheduler
   documentation`_
#. Within Scheduler, schedule the task to send newsletters, and
   optionally to fetch bounced emails
#. Configure mail transport in ``LocalConfiguration.php`` (more info in
   `API documentation`_):

.. code:: php

 <?php
     return array(
         'MAIL' => array(
             'transport' => 'smtp',
             'transport_smtp_server' => 'smtp.example.com:587',
         ),
     );

Send your first newsletter
--------------------------

#. In TYPO3 Backend, in list mode. Create a new RecipientList (eg:
   http://www.example.com/typo3/alt\_doc.php?edit[tx\_newsletter\_domain\_model\_recipientlist][1]=new)

   #. Pick a few BE-users
   #. Save

#. Select the module “Newsletter”
#. Select the page you want to send as newsletter
#. On the tab “Newsletter > Status”, check that there is no errors
#. On the tab “Newsletter > Settings”, enter the name and email of the
   sender
#. On the tab “Newsletter > Sending”

   #. Select a RecipientList
   #. Select the time when the Newsletter will start sending
   #. Click on the button “Add to Queue”

The newsletter will be sent via the Scheduler task at the time it was
planned, or, for testing purpose, you can manually trigger the task
within Scheduler. Statistics are available as soon as a newsletter is
queued. So it is possible to check what’s going on.

Configuration
=============

Recipient list
--------------

There is several ways to define a list of recipients. Those are:

-  **SQL**: specify SQL queries to fetch data from any table with at least
   an ‘email’ field
-  BE-users: select existing backend users
-  FE-Groups with FE-Users: select frontend groups containing frontend
   users
-  Page with FE-Users: select pages where frontend users are stored
-  CSV file: upload a CSV file containing users
-  CSV list: specify CSV content (eg: copy/paste from a file)
-  CSV url: specify an URL to fetch a CSV file from
-  HTML: fetch an URL and parse its content to find emails

SQL Recipient List are, by far, the most flexible and powerful way do
define a list of recipient. It allow dynamic composition of string that
can be used in newsletter content. And it also allow to take action (SQL
queries) upon specific event (bounced email, unsubscribe). Thus we
**strongly recommend the use of SQL Recipient List** and to read the
[[SQL examples for Recipient List]].

For CSV, when asked for ``CSV Fields``, you should enter the column names,
eg: ``email,firstname,lastname``. Then file/list/url should only contains
the values without any column headers, eg: ``me@example.com,John,Connor``.

Bounce account
--------------

A new record type called “Bounce account”. You should select a bounce
account for newsletter. The bounce account is used in two ways:

-  To provide an email address for the mail to bounce to ("Return-Path:" header)
-  To provide login information to the email account for the bounce-system to login to.

Once a newsletter has a BounceAccount and the bounce Scheduler task is
enabled, the extension Newsletter will automatically attach the address
as return-path, read the rejected emails and disable/delete the failed
email addresses. The bounced emails will also appear in the statistics.

Unsubscription notifications
----------------------------

Unsubscription should be automated, for example via proper configuration
of SQL for bounced email. However it is possible to receive an email
whenever a recipient requests for unsubscription. The “Notification
email” field needs to be specified in extension configuration (in Extension
Manager).

Writing a newsletter
====================

Newsletter should be ‘self-contained’, meaning not linking to any
external resources except for images. CSS may be included inline but
with limited support (see http://www.email-standards.org and
http://www.campaignmonitor.com/css).

Markers substitutions
---------------------

Simple substitutions
~~~~~~~~~~~~~~~~~~~~

The extension Newsletter offers markers substitution in newsletter
content. Any fields available via RecpientList will be substituted in
content if found. There is three alternative syntax for markers:

-  :code:`###my_field###`
-  :code:`http://my_field`
-  :code:`https://my_field`

The last two variants are convenient to create hyperlink with TYPO3’s RTE.

By using the SQL RecipientList, we can select several other fields to
personalize the newsletter with recipient’s name, address, private
generated links or anything else needed.

In addition, the extension ‘Newsletter’ provide two built-in markers:

-  :code:`###newsletter_view_url###` URL to view the newsletter in a browser
-  :code:`###newsletter_unsubscribe_url###` URL to unsubscribe from the
   newsletter (will register a bounce with type :code:`NEWSLETTER_UNSUBSCRIBE`)

Advanced substitutions
~~~~~~~~~~~~~~~~~~~~~~

You can also use the fields as a boolean evaluation. If you write the
markers like this:

:code:`###:IF: my_field ###<p>Bla bla bla</p>###:ENDIF:###`

The :code:`<p>Bla bla bla</p>` will only be shown if the “my_field” field evaluates
to true in PHP. You can also make an else-branch:

:code:`###:IF: my_field ###<h1>Foo</h1>###:ELSE:###<h1>Bar</h1>###:ENDIF:###`

This can be useful to present different content to different recipients.

Commercial support
==================

If you need help with this extension, commercial support may be obtained
by contacting www.ecodev.ch.


.. _Scheduler
   documentation: http://docs.typo3.org/typo3cms/extensions/scheduler/Installation/Index.html
.. _API documentation: _http://api.typo3.org/typo3cms/current/html/class_t_y_p_o3_1_1_c_m_s_1_1_core_1_1_mail_1_1_mailer.html
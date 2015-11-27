.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _user-manual:

Users Manual
============

Target group: **Editors**

Sending bulk email requires careful planning, you should check very carefully 
the content and formatting of your newsletter and the recipients who you intend 
to send it to. It is very easy to accidentally send unsolicited emails with this 
extension to numerous people. The developers of this extension take no 
responsibility for your angry email recipients.

.. warning::

Please think very hard what you are doing when using this tool, once emails have 
been sent by the system it is near impossible to recall them.

Having said that, it is very important to make small test sends of your 
newsletter to your own email address to proof your work before you schedule it 
for final delivery.

.. tip::

Give yourself enough forward scheduling time. If you schedule a newsletter it 
may be possible to stop it before it has been sent. If you spot a problem 
quickly advise your Administrator as soon as possible as they probably have the 
ability to stop the mails.

.. _user-sending_first_newsletter:

Sending your first newsletter
-----------------------------

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

Writing a newsletter
--------------------

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

The :code:`<p>Bla bla bla</p>` will only be shown if the “my_field” field 
evaluates to true in PHP. You can also make an else-branch:

:code:`###:IF: my_field ###<h1>Foo</h1>###:ELSE:###<h1>Bar</h1>###:ENDIF:###`

This can be useful to present different content to different recipients.


.. _user-faq:



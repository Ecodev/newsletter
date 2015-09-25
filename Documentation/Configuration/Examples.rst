.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _configuration-Examples:

Configuration Examples
======================

Target group: **Developers**

.. contents::

.. _configuration-Examples-Recipient_List:

Recipient List SQL
------------------

SQL can use special markers in query which will be substituted when it
makes sense:


======================================  ================  ============== =============== =============
  Marker                                 registerBounce    registerOpen   registerClick   Description
======================================  ================  ============== =============== =============
:code:`###EMAIL###`                            ✓                ✓              ✓          replaced by the escaped recipient email address
:code:`###BOUNCE_TYPE###`                      ✓                                          replaced by the bounce type
:code:`###BOUNCE_TYPE_SOFT###`                 ✓                                          constant for soft bounce
:code:`###BOUNCE_TYPE_HARD###`                 ✓                                          constant for hard bounce
:code:`###BOUNCE_TYPE_UNSUBSCRIBE###`          ✓                                          constant for unsubscribe bounce
======================================  ================  ============== =============== =============


The following examples may use fields which does not exist by default in
database. Be sure to check the query.

.. _configuration-Examples-Recipient_List-extension_addresses:

With extension addresses
~~~~~~~~~~~~~~~~~~~~~~~~

We use a new field
``tx_addresses_domain_model_person.tx_newsletter_bounce`` to count
bounces, up to the value 10. When 10 is reached the recipient will be
excluded. Thus we allow for 10 soft bounces, 2 hard bounces or 1
unsubscription before exclusion.

SQL to select recipients:

.. code:: sql

    -- Select recipient without too many bounces
    SELECT `email`.`email_address` AS `email`, `person`.*
    FROM `tx_addresses_domain_model_person` AS `person`
    JOIN `tx_addresses_domain_model_email` AS `email` ON (`person`.`uid` = `email`.`contact`)
    WHERE NOT `person`.`deleted` AND NOT `person`.`hidden` AND `person`.`tx_newsletter_bounce` < 10;

SQL to register a bounced email:

.. code:: sql

    UPDATE `tx_addresses_domain_model_person` AS `person`
    JOIN `tx_addresses_domain_model_email` AS `email` ON (`person`.`uid` = `email`.`contact`)
    SET
    -- Increment bounce level
    `person`.`tx_newsletter_bounce` = `person`.`tx_newsletter_bounce` + CASE ###BOUNCE_TYPE###
        WHEN ###BOUNCE_TYPE_SOFT### THEN 1
        WHEN ###BOUNCE_TYPE_HARD### THEN 5
        WHEN ###BOUNCE_TYPE_UNSUBSCRIBE### THEN 10
        ELSE 0 END,
    -- Hide person in case of unsubscribe
    `person`.`hidden` = `person`.`hidden` OR ###BOUNCE_TYPE### = ###BOUNCE_TYPE_UNSUBSCRIBE###

    WHERE email_address = ###EMAIL###

.. _configuration-Examples-Recipient_List-extension_tt_address:

With extension tt_address
~~~~~~~~~~~~~~~~~~~~~~~~~

We do not count bounces, but only exclude recipient on first hard bounce
or unsubscribe.

SQL to select recipients:

.. code:: sql

    --Select @tt_address@ records which are stored on page 1, 2 and 3. And also select the page title:
    SELECT DISTINCT tt_address.uid,name,address,phone,fax,email,tt_address.title,zip,city,country,www,company,pages.title AS pages_title
    FROM pages
    INNER JOIN tt_address ON pages.uid = tt_address.pid
    WHERE pages.uid IN (1, 2, 3)
    AND email != ''
    AND NOT pages.deleted
    AND NOT pages.hidden
    AND NOT tt_address.deleted
    AND NOT tt_address.hidden

SQL to register a bounced email:

.. code:: sql

    -- delete if it's an unsubscribe request or hard bounce
    UPDATE tt_address
    SET deleted = 1
    WHERE email = ###EMAIL### AND
    (###BOUNCE_TYPE### = ###BOUNCE_TYPE_UNSUBSCRIBE### OR ###BOUNCE_TYPE### = ###BOUNCE_TYPE_HARD###)

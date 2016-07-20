.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _developer:

Developer Corner
================

Target group: **Developers**

This extension is developed on GitHub, if you wish to contribute to the
`project`_ you are most welcome to participate. Be sure to contact the team
before starting to work on significant modifications.

.. _project: https://github.com/Ecodev/newsletter

The following figure shows the original model of the extension, while it has
changed overtime, it still gives a good idea of how it the code is structured:


.. figure:: ../Images/model.png
   :alt: Original model of Newsletter

.. _developer-hooks:

Hooks
-----

There are only two hooks available: ``substituteMarkersHook`` and
``getConfiguredMailerHook``. See source code for details.


If you need additional hooks, post your request to the
projects `issues`_ page on GitHub with a detailed explanation of your use-case.

.. _issues: https://github.com/Ecodev/newsletter/issues

.. _developer-api:

API
---

There is currently no published API for this extension however there is a
Doxyfile configuration in the Documentation folder. This file can be used to
generate source documentation in combination with `Doxygen`_.

.. _Doxygen: http://www.doxygen.org

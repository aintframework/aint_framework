Introduction
============

Overview
--------
**aint framework** is a functional and procedural programming framework for modern PHP (at least PHP version 5.4 is required).

Object Oriented Programming has conquered the world of PHP and now is the mainstream programming paradigm for creating PHP web applications. **aint framework** offers an alternative way of approaching, with the two key concepts being: *data* and *functions* to process it. It does not apply any additional restrictions.

The framework consists of namespaces or packages. For example, ``aint\mvc`` namespace includes functions for routing and dispatching an http request.

There is practically no learning curve to **aint framework**. Just like with the good old plain PHP: you have functions,  you call them with parameters and you write your own functions. However, novice developers may find it easier to write potentially bad code, because of the lack of restrictions (unlike in OOP).

A few facts about **aint framework**:

1. `PHPUnit <www.phpunit.de/manual/current/en/installation.html>`_ library is used for testing
2. Unique :doc:`coding standard </development/standard>`
3. Data is presented with PHP's built-in types such as integer, string, array
4. PHP classes are only used to :doc:`present errors </guides/error-handling>` (via exceptions mechanism)
5. There are no static dependencies on data in the framework's code

Installation
------------
\1. If you use `composer <http://getcomposer.org/>`_ for your project, add this to your ``composer.json``::

   "require": {
        "aintframework/aint_framework": "dev-master"
    }

and install the dependency.

\2. Alternatively, download the framework from `the GitHub page <https://github.com/aintframework/aint_framework>`_.

\3. Recommended way of starting a project is to use :doc:`quickstart/skeleton`.

Usage
^^^^^
At the moment, packages (namespaces) autoloading is `not achievable with PHP <http://blog.lcf.name/2012/06/php-namespace-autoload.html>`_, so all namespaces have to be included into the project explicitly.

It's recommended to add **aint framework** to the ``include_path``:

.. code-block:: php

 <?php
 set_include_path(get_include_path()
     . PATH_SEPARATOR . realpath(dirname(__FILE__) . '/../vendor/aintframework/aint_framework/library'));

with that you can use framework's features as follows:

.. code-block:: php

    <?php
    require_once 'aint/mvc/dispatching.php';
    use aint\mvc\dispatching;

    const actions_namespace = 'my_actions';
    const error_handler = 'my_error_handler';

    dispatching\run_default(actions_namespace, error_handler);

Read more about using the framework for building a simple web application in the :doc:`quickstart` section.
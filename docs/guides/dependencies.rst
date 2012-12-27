Dealing with Dependencies
=========================

PHP frameworks have evolved along with PHP itself from using global state, registry pattern, singleton to Dependency Injection, Service Locator and Inversion of Control principles.

**aint framework** has learned from this path, from practical use cases and tasks being solved using PHP. The result is a solution as obvious and simple as it gets.

Imagine you have a resource (an object in OOP, simply data in **aint framework**): *a db connection*. You want this resource to be shared within the application. Essentially what you want is this resource to be **static**. PHP has a keyword for this purpose and **aint framework** encourages you to use it, to keep things simple:

.. code-block:: php

    <?php
    function get_db() {
        static $db;
        if ($db === null)
            $db = aint\db\db_connect(/* ... */);
        return $db;
    }

When you first ask for this resource/data it'll be created/fetched/composed/calculated and then the same one will be returned to all consecutive calls.

If you need the dependency management to follow some other logic, e.g. new resource each time, - you can code it in accordingly:

.. code-block:: php

    <?php
    function get_db() {
        // new connection each time
        $db = aint\db\db_connect(/* ... */);
        return $db;
    }

Here you're not limited with the features provided by a particular DI container implementation. You manage dependencies yourself.

.. note::
    The only tricky bit is testing static dependencies. `On testing static dependencies <http://blog.lcf.name/2012/12/testing-shit-out-of-your-php-application.html>`_

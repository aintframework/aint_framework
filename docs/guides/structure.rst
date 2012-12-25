Application Structure
=====================

Being a framework is not only providing tools to use, but recommendations on how it's best to use them, as well.

This is the top directory of the skeleton project::

    /albums_manager
        /src
        /vendor
        /www

``vendor`` - is the directory where all external application dependencies will reside, including the **aint framework** itself.

``src`` - is the container for the actual code. All the backend programming happens inside.

``www`` is the document root for the web server, it contains various public resources such as images, styles. It also serves the ``index.php``, the entry point of your application. It looks like this:

.. code-block:: php

    <?php
    set_include_path(get_include_path()
        . PATH_SEPARATOR . realpath(dirname(__FILE__) . '/../src')
        . PATH_SEPARATOR . realpath(dirname(__FILE__) . '/../vendor/aintframework/aint_framework/library'));

    require_once 'app/controller.php';
    app\controller\run();

It does three things:
1. Adds both application source and **aint framework** to ``include_path`` so their components can be included easily when needed.
2. Includes ``app/controller.php`` file, the package containing the `Front Controller <http://en.wikipedia.org/wiki/Front_Controller_pattern>`_ function (the actual entry point of any HTTP request).
3. Runs the app (through invoking the function playing the role of front controller).

MVC
^^^

And here is the suggested MVC application structure::

    /src
        /app
            /controller
            /model
            /view
            controller.php
            model.php
            view.php

Most important thing about this structure, it's very specific and thorough about implementing the Model-View-Controller pattern. For each piece of code, whether it's a config file, a function, a constant or a template: you will have to decide what it is: Model, Controller or View, the code for which is located in namespaces ``app\model``, ``app\controller``, ``app\view`` accordingly.

``controller.php``, ``model.php``, ``view.php`` are simply files/namespaces to put some "upper-level", general code for which you don't feel like creating a subpackage.

Front Controller
^^^^^^^^^^^^^^^^

When the application is run, function ``app\controller\run`` is executed.

*todo* complete
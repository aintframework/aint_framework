Error Handling
==============

To handle exceptional situations (when an operation/function cannot be completed for some reason) **aint framework** uses the PHP's `mechanism of exceptions <http://php.net/manual/en/language.exceptions.php>`_.

There may be many exceptions defined per package:

.. code-block:: php

    <?php
    namespace app\model\posts;

    /**
     * Error thrown when the title of a post exceeds allowed length
     */
    class title_too_long_error extends \DomainException{};

    /**
     * Error thrown when connection to db failed
     */
    class db_connection_error extends \Exception{};

Usage is as per `official documentation <http://php.net/manual/en/language.exceptions.php>`_:

.. code-block:: php

    <?php
    namespace app\controller\actions\posts;

    use aint\http;
    use app\model\posts as posts_model;

    function add_action($request, $params) {
        try {
            posts_model\new_post($request);
        } catch (posts_model\title_too_long_error $error) {
            // if title was too long when adding a post, we redirect to main page
            return http\build_redirect('/');
        }
    }

.. note::
    Exceptions have to extend PHP's built-in ``Exception`` class and are all objects. This solution is not ideal but was chosen for now because there is no decent alternatives for exceptions handling in PHP.

    Conceptually, using what functions return would fit **aint framework** ideology perfectly (e.g. like in `Go programming language <http://blog.golang.org/2011/07/error-handling-and-go.html>`_), however PHP doesn't allow for that conveniently.

    The area of exceptions handling in **aint framework** is still being discussed. Suggestions, feedback and any other kind of input are highly appreciated.

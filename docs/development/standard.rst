Coding Standard
===============

**aint framework** coding standard used for the framework itself and also recommended for **aint framework** driven applications.

File Formatting
---------------
General
^^^^^^^
- closing tag ``?>`` is never permitted for PHP files
- usage of short tags e.g. ``<?=`` and ``<?`` is allowed for templates
- one source file should contain one namespace/package
- only Unix-style line termination is allowed (LF or \n)
- 80 - 120 characters is the recommended line length
- indentation is 4 spaces, tabs are not allowed.

File extensions
^^^^^^^^^^^^^^^
By convention, some of the recommended file name extensions are:

- ``.php`` for a full PHP source file, a package/namespace
- ``.phtml`` for PHP-powered templates
- ``.inc`` for configuration, localization files and alike

Naming Conventions
------------------
Namespaces (Packages)
^^^^^^^^^^^^^^^^^^^^^
Lowercase alphanumeric characters are permitted, with underscores used as separators:

- ``namespace aint\mvc\routing``
- ``namespace app\model\my_db``

Filenames
^^^^^^^^^
Files are named after the namespace (package) they contain:

- ``namespace aint\mvc\routing`` => ``aint/mvc/routing.php``
- ``namespace app\model\my_db`` => ``app/model/my_db.php``

Functions
^^^^^^^^^
Lowercase alphanumeric characters are permitted, with underscores used as separators:

- ``function build_response($body = '', $code = 200, $headers = [])``
- ``function render_template()``

Variables
^^^^^^^^^
Lowercase alphanumeric characters are permitted, with underscores used as separators:

- ``$quote_identifier``
- ``$router``

Constants
^^^^^^^^^
Lowercase alphanumeric characters are permitted, with underscores used as separators::

    /**
     * Http Request method types
     */
    const request_method_post = 'POST',
          request_method_get = 'GET',
          request_method_put = 'PUT',
          request_method_delete = 'DELETE';


Exceptions
^^^^^^^^^^
Lowercase alphanumeric characters are permitted, with underscores used as separators, e.g.::

    /**
     * Error thrown when an http request cannot be routed
     */
    class not_found_error extends \exception {};

Coding Style
------------
Strings
^^^^^^^
todo

Arrays
^^^^^^
todo

Functions
^^^^^^^^^
todo: declaration and usage

Control Statements
^^^^^^^^^^^^^^^^^^
For all control structures (if/switch/for/while) the opening brace is written on the same line as the conditional statement. The closing brace is always written on its own line. Any content within the braces must be indented.

The braces are only used when there is more than one line in the content:

.. code-block:: php

    if ($data[some_boolean_param])
        return 'yes';
    else
        return 'no';

Code Documentation
^^^^^^^^^^^^^^^^^^
todo
View (presentation)
===================

First of all, to take it off the table. The delete controller doesn't really need a template to be rendered, does it? After an album is successfully removed we simply redirect the browser to the albums index::

    todo: insert delete_action function once again

And we can get rid of ``templates/albums/delete.phtml`` now. The other templates we really need. Let's start with ``list.phtml``::

    insert the template

It outputs the albums in an HTML table, populating it using the extracted ``$albums`` variable. A few interesting things to notice are:

1. Usage of ``helpers\uri`` - the function to convert a target action-function back to an uri.
2. Usage of ``helpers\translate`` - a simple translation function to help with localization of your app.
3. ``htmlspecialchars`` is the PHP's function used to escape strings.

A bit more interesting are ``edit.phtml``::

    todo insert

and ``add.phtml``::
    todo insert

As the HTML form in both cases is almost the same and duplication of code is never good we move the common html to another file, ``/src/app/view/templates/album_add.phtml``::

    todo insert

to simplify things even further, we introduce the ``app\view\helpers\album_form`` function::

    todo insert, comment thoroughly

This is the beauty of the simplicity **aint framework** gives you. No more plugins, partials, helpers, dependency headaches. You are free to do the simplest thing possible.
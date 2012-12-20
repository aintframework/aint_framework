Model
=====

The `Service Layer <http://martinfowler.com/eaaCatalog/serviceLayer.html>`_ of our model will be the ``app\model\albums`` namespace. As it's a very simple, typical CRUD application, the facade of the model will look almost the same as the public interface (the controller)::

    todo: insert with comments

What differs this interface from the controller's one is an additional function to view a particular album. It's needed to show the information in the html form, when modifying an existing album.

As decided we'll store albums records in a SQLite database of one table::

    todo: insert sql

The database we'll create as ``/albums_manager/database/data`` file as it's not really the source code. To connect to the database and share this connection statically with the whole app, we'll need ``app\model\db`` namespace::

   todo: insert with comments

.. note::
    Read more about managing shared and not shared dependencies :doc:`in this tutorial </guides/dependencies>`

Model for this app is designed to use the `Table Data Gateway <http://martinfowler.com/eaaCatalog/tableDataGateway.html>`_ pattern, with ``app\model\db\albums_table`` being this gateway. Let's create it as well, adding functions required to read, write, update and delete data from the ``albums`` table::

    insert with comments

Notice, while framework is being used for the actual work, to wire it into your app you have to write all the functions you need inside the app's namespace. This idea is used for extending anything in **aint framework** and has functional programming paradigm behind it.

Instead of configuring instances, changing the *state* to suit your needs, like you would do in other popular frameworks you go right to extension the base code with your own.

.. note::
    Read more :doc:`here </guides/extension-over-configuration>`

Every function, essentially, is a proxy, a `partial application <http://en.wikipedia.org/wiki/Partial_application>`_ to the table gateway implementation provided by the framework. We specify namespaces for ``platform`` and ``driver`` to use.

Wiring Model and Controller together
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Let's return to the controller we prepared in the previous section::

    todo insert with new comments explaining all the additions

The only missing part now is :doc:`the View </quickstart/view>`
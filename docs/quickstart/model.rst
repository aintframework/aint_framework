Model
=====

The `Service Layer <http://martinfowler.com/eaaCatalog/serviceLayer.html>`_ of our model will be the ``app\model\albums`` namespace. As it's a very simple, typical CRUD application, the facade of the model will look almost the same as the public interface (the controller)::

    namespace app\model\albums;

    /**
     * Parameters of an album
     */
    const param_id = 'id',
          param_title = 'title',
          param_artist = 'artist';

    function list_albums() {
        // ...
    }

    function add_album($data) {
        // ...
    }

    function get_album($id) {
        // ...
    }

    function delete_album($id) {
        // ...
    }

    function edit_album($id, $data) {
        // ...
    }

What's different is the additional ``get_album`` function to view one particular album. It's needed to show the information in the html form, when modifying an existing album.

As decided we'll store albums records in a SQLite database::

    mkdir database
    sqlite3 database/data

of one table::

    create table albums (
        id integer primary key,
        title varchar(250),
        artist varchar(250)
    );

The database is created in ``/albums_manager/database/data`` file. We're placing it in a separate directory as it's not really the source code. To connect to the database and share this connection statically with the whole app, we'll need ``app\model\db`` namespace::

   namespace app\model\db;

   require_once 'app/model.php';
   use app\model;
   require_once 'aint/common.php';
   use aint\common;
   // including sqlite platform and driver packages
   require_once 'aint/db/platform/sqlite.php';
   require_once 'aint/db/driver/pdo.php';

   const driver = 'aint\db\driver\pdo';
   const platform = 'aint\db\platform\sqlite';

   function db_connect() {
       static $resource;
       if ($resource === null) { // we'll only connect to the db once
           $db_connect = driver . '\db_connect';
           $resource = $db_connect(model\get_app_config()['db']);
       }
       return $resource;
   }

This function uses model configuration that we add to ``src/app/model/configs/app.inc``::

    return [
        'db' => [
            'dns' => 'sqlite:/my/projects/dir/database/data'
        ]
    ];

.. note::
    You can override this and any other setting locally, by creating ``app.local.inc`` file in the same directory.

Model for this app is designed to use the `Table Data Gateway <http://martinfowler.com/eaaCatalog/tableDataGateway.html>`_ pattern, with ``app\model\db\albums_table`` being this gateway. Let's create it as well, adding functions required to read, write, update and delete data from the ``albums`` table. We'll need them all::

    namespace app\model\db\albums_table;

    require_once 'app/model/db.php';
    use app\model\db;
    require_once 'aint/db/table.php';

    const table = 'albums';

    /**
     * Partial application,
     * function delegating calls to aint\db\table package
     * adding platform and driver parameters
     *
     * @return mixed
     */
    function call_table_func() {
        $args = func_get_args();
        $func = 'aint\db\table\\' . array_shift($args);
        $args = array_merge([db\db_connect(), db\platform, db\driver, table], $args);
        return call_user_func_array($func, $args);
    }

    function select(array $where = []) {
        return call_table_func('select', $where);
    }

    function insert(array $data) {
        return call_table_func('insert', $data);
    }

    function update($data, $where = []) {
        return call_table_func('update', $data, $where);
    }

    function delete(array $where = []) {
        return call_table_func('delete', $where);
    }

Notice, while framework is being used for the actual work, to wire it into your app you have to write all the functions you need inside the app's namespace. This idea is used for extending anything in **aint framework** and has functional programming paradigm behind it.

Instead of configuring instances, changing the *state* to suit your needs, like you would do in other popular frameworks you go right to extension the base code with your own.

.. note::
    Read more :doc:`here </guides/extension-over-configuration>`

Every function, essentially, is a proxy, a `partial application <http://en.wikipedia.org/wiki/Partial_application>`_ to the table gateway implementation provided by the framework. We specify namespaces for ``platform`` and ``driver`` to use.

.. note::
    Read more about managing shared and not shared dependencies :doc:`in this tutorial </guides/dependencies>`

Let's return to the Service Layer, ``app\model\albums`` now and fill in missing details::

    namespace app\model\albums;

    // app uses table gateway pattern:
    require_once 'app/model/db/albums_table.php';
    use app\model\db\albums_table;

    /**
     * Parameters of an album
     */
    const param_id = 'id',
          param_title = 'title',
          param_artist = 'artist';

    function list_albums() {
        // simply return all records from the table
        return albums_table\select();
    }

    function add_album($data) {
        // insert data into the table
        albums_table\insert($data);
    }

    function get_album($id) {
        // look up all records in the table with id provided and return the first one
        return current(albums_table\select(['id' => $id]));
    }

    function delete_album($id) {
        // removes records from db with id provided
        albums_table\delete(['id' => $id]);
    }

    function edit_album($id, $data) {
        // updates records in db fulfilling the id = ? constraint with the data array provided
        albums_table\update($data, ['id' => $id]);
    }


Wiring Model and Controller together
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
Let's return to the controller we prepared in the previous section::

    namespace app\controller\actions\albums;

    require_once 'app/model/albums.php';
    use app\model\albums as albums_model;
    require_once 'app/view.php';
    use app\view;
    require_once 'aint/http.php';
    use aint\http;

    function list_action() {
        return view\render('albums/list',
            // passing the list of albums to the template
            ['albums' => albums_model\list_albums()]);
    }

    function add_action($request) {
        if (!http\is_post($request)) // if this isn't a POST request
            return view\render('albums/add'); // we simply show the HTML form
        else {
            // if it is a POST request, we add the new
            albums_model\add_album($request['params']);
            // and redirect to the index page
            return http\build_redirect('/');
        }
    }

    function edit_action($request, $params) {
        if (!http\is_post($request)) // if this isn't a POST request
            return view\render('albums/edit',  // we show the HTML form
                // filling current album data in the form
                ['album' => albums_model\get_album($params['id'])]);
        else {
            // if it is a POST request, we update the data in the model
            albums_model\edit_album($params['id'], $request['params']);
            // and redirect to the index page
            return http\build_redirect('/');
        }
    }

    function delete_action($request, $params) {
        // ask the model to delete the album
        albums_model\delete_album($params['id']);
        // and redirect to the index page
        return http\build_redirect('/');
    }

The only missing part now is :doc:`the View </quickstart/view>`
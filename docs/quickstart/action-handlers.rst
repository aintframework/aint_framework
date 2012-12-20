Controllers and Routing
=======================

Skeleton application uses the ``aint\mvc\routing\route_segment`` function for routing. We'll use it for our controllers as well. We'll create a namespace for albums-related controllers: ``app\controller\actions\albums`` and add the four action-functions to it::

    <?php
    namespace app\controller\actions\albums;

    function list_action($request, $params) {

    }

    function add_action($request, $params) {

    }

    function edit_action($request, $params) {

    }

    function delete_action($request, $params) {

    }

``route_segment`` will route ``/albums/add`` uri to ``app\controller\actions\albums\add_action`` function. ``$request`` holds data about the current HTTP request, while ``$params`` contains parameters of the route. For instance, ``/albums/edit/id/123`` will be routed to ``app\controller\actions\albums\edit_action`` with ``$params`` being this array: ``['id' => 123]``.

We'll need to "enable" the new actions namespace by adding it to ``app\controller``::

    // actions
    require_once 'app/controller/actions/index.php';
    require_once 'app/controller/actions/errors.php';
    require_once 'app/controller/actions/albums.php'; // - adding this

To have our application list albums on the index page, we'll change the ``app\controller\actions\index\index_action`` like this::

    function index_action() {
        return albums\list_action();
    }

To have all this working now, we'll also need to create some simple templates and have them rendered. Create these four files and leave them empty for now:

* ``/src/app/view/templates/albums/add.phtml``
* ``/src/app/view/templates/albums/edit.phtml``
* ``/src/app/view/templates/albums/delete.phtml``
* ``/src/app/view/templates/albums/list.phtml``

And make the change in the controllers::

    namespace app\controller\actions\albums;

    require_once 'app/view.php';
    use app\view;

    function list_action() {
        return view\render('albums/list');
    }

    function add_action($request) {
        return view\render('albums/add');
    }

    function edit_action($request, $params) {
        return view\render('albums/edit');
    }

    function delete_action($request, $params) {
        return view\render('albums/delete');
    }

Let's now drop in some logic, i.e. :doc:`The Model </quickstart/model>`

.. note::
    Find out more about recommended application structure :doc:`in this tutorial </guides/structure>`
View (presentation)
===================

First of all, to take it off the table: the delete controller doesn't really need a template to be rendered. After an album is successfully removed we simply redirect the browser to the albums index:

.. code-block:: php

    <?php
    function delete_action($request, $params) {
        // ask the model to delete the album
        albums_model\delete_album($params['id']);
        // and redirect to the index page
        return http\build_redirect('/');
    }

So we can delete ``templates/albums/delete.phtml`` now. The other templates we do need. Let's start with ``list.phtml``:

.. code-block:: php

    <?php
    require_once 'app/view/helpers.php';
    use app\view\helpers;

    helpers\head_title($title = helpers\translate('My Albums'));
    ?>
    <h1><?= htmlspecialchars($title) ?></h1>
    <p>
        <a href="<?= helpers\uri('albums\add_action') ?>">Add new album</a>
    </p>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Title</th>
            <th>Artist</th>
            <th>Controls</th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($albums as $album) : ?>
        <tr>
            <td><?= htmlspecialchars($album['title']) ?></td>
            <td><?= htmlspecialchars($album['artist']) ?></td>
            <td>
                <a href="/albums/edit/id/<?= $album['id'] ?>">Edit</a> |
                <a href="/albums/delete/id/<?= $album['id'] ?>">Delete</a>
            </td>
        </tr>
            <? endforeach ?>
        </tbody>
    </table>

It outputs the albums in an HTML table, populating it using the extracted ``$albums`` variable. A few interesting things to notice are:

1. Usage of ``helpers\uri`` - the function to convert a target action-function back to an uri.
2. Usage of ``helpers\translate`` - a simple translation function to help with localization of your app.
3. ``htmlspecialchars`` is the PHP's function used to escape strings.

A bit more interesting are ``edit.phtml``:

.. code-block:: php

    <?php
    require_once 'app/view/helpers.php';
    use app\view\helpers;

    helpers\head_title($title = helpers\translate('Edit album'));
    ?>
    <h1><?= htmlspecialchars($title) ?></h1>
    <p><?= helpers\album_form('/albums/edit/id/' . $album['id'], $album) ?></p>

and ``add.phtml``:

.. code-block:: php

    <?php
    require_once 'app/view/helpers.php';
    use app\view\helpers;

    helpers\head_title($title = helpers\translate('Add new Album'));
    ?>
    <h1><?= htmlspecialchars($title) ?></h1>
    <p><?= helpers\album_form('/albums/add') ?></p>

As the HTML form in both cases is almost the same and duplication of code is never good we move the common html to another file, ``/src/app/view/templates/album_form.phtml``:

.. code-block:: php

    <form action="<?= $action ?>" method="post" class="form-horizontal well">
        <fieldset>
            <div class="control-group">
                <label class="control-label" for="title">Title</label>

                <div class="controls">
                    <input type="text" class="input-xlarge" id="title" name="title"
                           value="<?= htmlspecialchars($album['title']) ?>"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="artist">Artist</label>

                <div class="controls">
                    <input type="text" class="input-xlarge" id="artist" name="artist"
                           value="<?= htmlspecialchars($album['artist']) ?>"/>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </fieldset>
    </form>

to simplify things further, we introduce the ``app\view\helpers\album_form`` function:

.. code-block:: php

    <?php
    function album_form($action, $album = []) {
        $default_album_data = [
            'title' => '',
            'artist' => '',
        ];
        $album = array_merge($default_album_data, $album);
        return view\render_template('album_form', ['album' => $album, 'action' => $action]);
    }

This is the beauty of the simplicity **aint framework** gives you. No more plugins, partials, helpers, dependency headaches. You are free to do the simplest thing possible.
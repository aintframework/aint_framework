Application Overview
====================

The application is an online albums manager, allowing you to create, edit, delete and view music albums in your collection. As any other CRUD task, this one will need four pages:

* list albums in the collection
* add new album to the collection
* edit (or update) an existing album
* delete an existing album

To store information we'll use a relational database. Could be anything, but for demonstration purposes we'll go with a SQLite3 database. One table is needed to store the albums records with these being an album characteristics:

* id (integer, auto-increment) - a unique album identifier
* artist (string)
* title (string)

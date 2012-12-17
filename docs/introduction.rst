Overview & Installation
=======================

Overview
--------

aint framework is a functional and procedural programming framework for modern PHP (at least PHP version 5.4 is required).

Object Oriented Programming has conquered the PHP world and now is the mainstream programming paradigm for creating PHP web applications. aint framework offers an alternative way of approaching, with the two key concepts being: *data* and *functions* to process it. It does not apply any additional restrictions.

The framework consists of namespaces, such as ``aint\mvc``. This namespace includes other namespaces with functions for routing and dispatching a http request. It does not suggest you to use a completely made up thing such as a *Dispatcher* interface/class/object, but instead provides a ``dispatch`` function.

There is practically no learning curve with aint framework. Just like with the good old plain PHP: you have functions and you call them plus you write your own functions. However, novice developers may find it easier to write potentially bad code with aint framework because of the lack of restrictions (unlike in OOP). 

One of the key ideas behind aint framework, based on experience of the authors: if somebody has to do bad/quick/dirty code for whatever reason - OOP only makes it worse.

A few facts about aint framework:

1. PHPUnit library is used for testing
2. *todo*

Installation
------------
*todo* skeleton_application reference

Recommended installation is via composer. Add this to your ``composer.json``::

   "require": {
        "aintframework/aint_framework": "dev-master"
    }

and install the dependency.

Alternatively, download `the Git repostitory <https://github.com/aintframework/aint_framework>`_
.

Usage
^^^^^
aint framework does not rely on autoloading, as autoloading of namespaces is not available in PHP at the moment. Framework's namespaces have to be included into the project explicitly. It's recommended to add aint framework to the ``include_path``::

 set_include_path(get_include_path()
     . PATH_SEPARATOR . realpath(dirname(__FILE__) . '/../vendor/aintframework/aint_framework/library'));

*todo* examples

 
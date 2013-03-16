# Mango
## A MongoDB object document mapper for PHP

Mango is currently only a few days old. At the moment you can only create your own document classes and store or remove them from a collection.

Please come again in a few weeks when Mango will be much more usefull to you.

[![Build Status](https://travis-ci.org/WebCodr/Mango.png?branch=master)](https://travis-ci.org/WebCodr/Mango)

### Requirements

- PHP 5.4
- MongoDB driver for PHP (min. 1.2.0)
- Composer

### Setup

#### Add Mango to your project

Add the Mango package to your project's composer.json

~~~
"webcodr/mango": "*@dev"
~~~

Run `php composer.phar install`

#### Create a document class

~~~ php
<?php

namespace Document\User;

use Mango\Document;
use Mango\DocumentInterface;

class User implements DocumentInterface
{
    use Document;

    public $name;
    public $email;
}
~~~

You don't have to set a collection name. Mango uses the class name in lower case as collection name.

If you want to set a custom collection name, just override the method `getCollectionName()` in your own document classes.

There's no need to provide an id. Mango's document base class adds automatically the property '_id' with a fresh MongoId object.

#### Save a document

~~~ php
<?php

use Mango\Mango;
use Mango\DocumentManager;

use Document\User;

$mango = new Mango('mongodb://devserver:27017/galactica-actual');
$dm = new DocumentManager($mango);
$user = new User();
$user->name = 'William Adama';
$user->email 'william.adama@galactica.colonial-forces.gov';
$dm->store($user);
~~~

#### Remove a document

~~~ php
$dm->remove($user);
~~~



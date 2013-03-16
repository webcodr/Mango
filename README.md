# Mango
## A MongoDB object document mapper for PHP

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

namespace Mango\Tests\Document;

use Mango\Document;
use Mango\DocumentInterface;

class User implements DocumentInterface
{
    use Document;

    public $created_at;
    public $updated_at;
    public $name;
    public $email;

    private function addFields()
    {
        $this->addField(
            'name',
            []
        );

        $this->addField(
            'created_at',
            [
                'type' => 'DateTime',
                'index' => true,
                'default' => 'now'
            ]
        );

        $this->addField(
            'updated_at',
            [
                'type' => 'DateTime',
                'index' => true,
                'default' => 'now'
            ]
        );
    }
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

#### Querying

~~~ php
$user = User::where($dm, ['name' => 'William Adama']);
echo $user->count(); // result = 1
echo $user->head()->email; // result = william.adama@galactica.colonial-forces.gov
~~~



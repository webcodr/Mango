# Mango

[![Build Status](https://travis-ci.org/WebCodr/Mango.png?branch=master)](https://travis-ci.org/WebCodr/Mango)

### A MongoDB object document mapper for PHP
#### Inspired by [Mongoid for Ruby](http://mongoid.org/en/mongoid/index.html)

### Requirements

- PHP 5.4
- MongoDB driver for PHP (min. 1.2.0)
- Composer

### Setup

#### Add Mango to your project

~~~ bash
$ php composer.phar require webcodr/mango:*
~~~

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
        $this->addField('name', ['type' => 'String']);
        $this->addField('email', ['type' => 'String']);

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
$user->store();
~~~

#### Remove a document

~~~ php
$user->remove();
~~~

#### Querying

The methods `find` and `where` return a \Mango\Persistence\Cursor object or an object of the class \Collection\MutableMap. It depends on which method is called.

MutableMap is part of another WebCodr project called Collection. It provides several classes to replace PHP arrays and is much more fun to use. Check it out [here](https://github.com/WebCodr/Collection).

Mango uses object hydration to automatically provide a result with document objects.

##### Find documents by id

###### One id

~~~ php
$user = User::find('abc')->first();
~~~

###### Multiple ids

~~~ php
$user = User::find('abc', 'def', 'ghi')->first();
~~~

##### Find all documents in collection

~~~ php
User::where()->each(function($user) {
    echo $user->name;
});
~~~

##### Find a document with certain field value

~~~ php
$user = User::where(['name' => 'William Adama']);
echo $user->count(); // result = 1
echo $user->head()->email; // result = william.adama@galactica.colonial-forces.gov
~~~



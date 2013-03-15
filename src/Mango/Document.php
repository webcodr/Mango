<?php

namespace Mango;

use Collection\MutableMap;

class Document implements DocumentInterface {
    protected $database;
    protected $collection;
    protected $fields = array();
    public $_id;

    public function __construct($database, $collection)
    {
        $this->database = $database;
        $this->collection = $collection;
        $this->_id = new \MongoId();
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function getProperties()
    {
        $properties = get_object_vars($this);

        return new MutableMap($properties);
    }
}
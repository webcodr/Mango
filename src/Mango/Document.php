<?php

namespace Mango;

use Collection\MutableMap;

abstract class Document implements DocumentInterface {
    protected $database;
    protected $fields = array();
    public $_id;

    public function __construct($database)
    {
        $this->database = $database;
        $this->_id = new \MongoId();
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function getCollection()
    {
        $name = join('', array_slice(explode('\\', get_class($this)), -1));
        $name = strtolower($name);

        return $name;
    }

    public function getProperties()
    {
        $properties = get_object_vars($this);

        return new MutableMap($properties);
    }
}
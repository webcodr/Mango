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
        $reflectionClass = new \ReflectionClass($this);
        $properties = new MutableMap();

        foreach ($reflectionClass->getProperties() as $property) {
            $name = $property->name;

            if ($property->class == $reflectionClass->name) {
                $properties->setProperty($name, $this->{$name});
            }
        }

        return $properties;
    }
}
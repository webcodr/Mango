<?php

namespace Mango;

use Mango\DocumentManager;
use Collection\MutableMap;

trait Document
{
    protected $database;
    protected $fields = array();
    public $_id;

    public function __construct()
    {
        $this->_id = new \MongoId();
    }

    public static function getCollectionName()
    {
        $name = join('', array_slice(explode('\\', __CLASS__), -1));
        $name = strtolower($name);

        return $name;
    }

    public static function where(DocumentManager $dm, array $query)
    {
        return $dm->where(self::getCollectionName(), $query, __CLASS__);
    }

    public function hydrate(array $document)
    {
        foreach ($document as $property => $value) {
            $this->{$property} = $value;
        }
    }

    public function getProperties()
    {
        $reflectionClass = new \ReflectionClass($this);
        $properties = new MutableMap();

        foreach ($reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->name;
            $properties->setProperty($name, $this->{$name});
        }

        return $properties;
    }
}
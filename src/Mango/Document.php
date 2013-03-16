<?php

namespace Mango;

use Mango\DocumentManager;
use Mango\Helper\Hydrator;
use Mango\Helper\Dehydrator;

use Collection\MutableMap;

trait Document
{
    private $fields = array();
    public $_id;

    public function __construct()
    {
        $this->_id = new \MongoId();

        $this->addField(
            '_id',
            [
                'type' => 'Id'
            ]
        );


        $this->addFields();
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

    private function addFields() {

    }

    private function addField($field, $config = [])
    {
        $this->fields[$field] = [
            'name' => $field,
            'config' => $config
        ];
    }

    public function getField($field)
    {
        return $this->fields[$field];
    }

    private function getFieldConfig($name, $property = null)
    {
        if ($property === null) {
            return $this->fields[$name]['config'];
        }

        return (isset($this->fields[$name]['config'][$property])) ? $this->fields[$name]['config'][$property] : null;
    }

    public function hydrate(array $data)
    {
        $hydrator = new Hydrator();

        foreach ($data as $property => $value) {
            $type = $this->getFieldConfig($property, 'type');
            $value = $hydrator->hydrate($value, $type);

            $this->{$property} = $value;
        }
    }

    public function getProperties()
    {
        $reflectionClass = new \ReflectionClass($this);
        $properties = new MutableMap();
        $dehydrator = new Dehydrator();

        foreach ($reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->name;
            $type = $this->getFieldConfig($name, 'type');
            $default = $this->getFieldConfig($name, 'default');
            $value = $dehydrator->dehydrate($this->{$name}, $type, $default);

            $properties->setProperty($name, $value);
        }

        return $properties;
    }
}
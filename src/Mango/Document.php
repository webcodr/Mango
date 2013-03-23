<?php

namespace Mango;

use Mango\Helper\Hydrator;
use Mango\Helper\Dehydrator;

use Collection\MutableMap;

/**
 * Trait Document
 * @package Mango
 */

trait Document
{
    private $fields = array();
    public $_id;

    /**
     * Constructor
     */

    public function __construct(array $properties = array())
    {
        // set new MongoId on object creation
        $this->_id = new \MongoId();

        // config for id field
        $this->addField('_id', ['type' => 'Id']);

        // call hook method (can be overridden in parent class)
        $this->addFields();

        if (!empty($properties)) {
            $this->hydrate($properties);
        }
    }

    /**
     * Get MongoId string
     *
     * @return string
     */

    public function getId()
    {
        return (string)$this->_id;
    }

    /**
     * Store document to MongoDB
     *
     * @return $this
     */

    public function store()
    {
        $this->ensureIndices();
        Mango::getDocumentManager()->store($this);

        return $this;
    }

    /**
     * Remove document from MongoDB
     *
     * @return $this
     */

    public function remove()
    {
        Mango::getDocumentManager()->remove($this);
        $this->reset();

        return $this;
    }

    /**
     * Reset all public document properties
     */

    private function reset()
    {
        foreach($this->getProperties() as $name => $value) {
            if ($name == '_id') {
                $this->_id = new \MongoId();
            } else {
                $this->{$name} = null;
            }
        }
    }

    /**
     * Get collection name from class name (lower case)
     *
     * @return string
     */

    public static function getCollectionName()
    {
        $name = join('', array_slice(explode('\\', __CLASS__), -1));
        $name = strtolower($name);

        return $name;
    }

    /**
     * Get document(s) by id(s)
     *
     * @return Persistence\Cursor
     * @throws \InvalidArgumentException
     */

    public static function find()
    {
        $args = func_get_args();

        if (empty($args)) {
            throw new \InvalidArgumentException('No Ids given.');
        }

        if (count($args) === 1) {
            $id = current($args);
            return self::where(['_id' => new \MongoId($id)]);
        }

        $ids = [];

        foreach ($args as $id) {
            $ids[] = new \MongoId($id);
        }

        return self::where(['_id' => ['$in' => $ids]]);
    }

    /**
     * Execute query
     *
     * @param array $query
     * @return \Mango\Persistence\Cursor
     */

    public static function where(array $query = [])
    {
        $dm = Mango::getDocumentManager();

        return $dm->where(self::getCollectionName(), $query, __CLASS__);
    }

    /**
     * Hook method for parent class to initialize its field config
     */

    private function addFields() {

    }

    /**
     * Add field config
     *
     * @param $field
     * @param array $config
     */

    private function addField($field, $config = [])
    {
        $this->fields[$field] = [
            'name' => $field,
            'config' => $config
        ];
    }

    /**
     * Get field data
     *
     * @param $field
     * @return mixed
     */

    public function getField($field)
    {
        return $this->fields[$field];
    }

    /**
     * Get field config or a specific config property
     *
     * @param $name
     * @param null $property
     * @return mixed
     */

    private function getFieldConfig($name, $property = null)
    {
        // return the whole config
        if ($property === null) {
            return $this->fields[$name]['config'];
        }

        return (isset($this->fields[$name]['config'][$property])) ? $this->fields[$name]['config'][$property] : null;
    }

    /**
     * Hydrate the object with cursor data
     *
     * @param array $data
     */

    private function hydrate(array $data)
    {
        $hydrator = new Hydrator();

        foreach ($data as $property => $value) {
            $type = $this->getFieldConfig($property, 'type');
            $value = $hydrator->hydrate($value, $type);

            $this->{$property} = $value;
        }
    }

    /**
     * Hook method to prepare for storage
     */

    private function prepare()
    {
        return null;
    }

    /**
     * Get all public properties
     *
     * @return MutableMap
     */

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

    /**
     * Ensure that indices are set
     */

    private function ensureIndices()
    {
        foreach ($this->getProperties() as $property => $value) {
            if ($this->getFieldConfig($property, 'index') === true) {
                Mango::getDocumentManager()->index($this, $property);
            }
        }
    }

    /**
     * Get all public properties and dehydrates them for storage
     *
     * @return MutableMap
     */

    public function getDehydratedProperties()
    {
        $properties = new MutableMap();
        $dehydrator = new Dehydrator();
        $this->prepare();

        foreach ($this->getProperties() as $name => $property) {
            $type = $this->getFieldConfig($name, 'type');
            $default = $this->getFieldConfig($name, 'default');
            $value = $dehydrator->dehydrate($this->{$name}, $type, $default);

            $properties->setProperty($name, $value);
        }

        return $properties;
    }
}
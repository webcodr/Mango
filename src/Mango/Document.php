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
    private $hydrator;
    public $_id;

    /**
     * Constructor
     */

    public function __construct(array $attributes = [])
    {
        // set new MongoId on object creation
        $this->_id = new \MongoId();

        // config for id field
        $this->addField('_id', ['type' => 'Id']);

        // call hook method (can be overridden in parent class)
        $this->addFields();

        $this->setHydrator(new Hydrator());

        if (!empty($attributes)) {
            $this->update($attributes);
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
        foreach ($this->getAttributes() as $name => $value) {
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

    private function addFields()
    {

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
     * @param null $attribute
     * @return mixed
     */

    private function getFieldConfig($name, $attribute = null)
    {
        // return the whole config
        if ($attribute === null) {
            return $this->fields[$name]['config'];
        }

        return (isset($this->fields[$name]['config'][$attribute])) ? $this->fields[$name]['config'][$attribute] : null;
    }

    /**
     * Set Hydrator
     *
     * @param $hydrator
     */

    private function setHydrator($hydrator)
    {
        $this->hydrator = $hydrator;
    }

    /**
     * Get Hydrator
     *
     * @return mixed
     */

    private function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * Hydrate given value
     *
     * @param $attribute
     * @param $value
     * @return mixed
     */

    private function hydrate($attribute, $value)
    {
        $type = $this->getFieldConfig($attribute, 'type');

        return $this->getHydrator()->hydrate($value, $type);
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

    public function getAttributes()
    {
        $reflectionClass = new \ReflectionClass($this);
        $attributes = new MutableMap();

        foreach ($reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC) as $attribute) {
            $name = $attribute->name;
            $attributes->set($name, $this->{$name});
        }

        return $attributes;
    }

    /**
     * Update document properties from given array
     *
     * @param array $attributes
     * @return $this
     */

    public function update(array $attributes = [])
    {
        $this
            ->getAttributes()
            ->update($attributes)
            ->each(function($value, $attribute) {
                $this->{$attribute} = $this->hydrate($attribute, $value);
            });

        return $this;
    }

    /**
     * Ensure that indices are set
     */

    private function ensureIndices()
    {
        foreach ($this->getAttributes() as $attribute => $value) {
            if ($this->getFieldConfig($attribute, 'index') === true) {
                Mango::getDocumentManager()->index($this, $attribute);
            }
        }
    }

    /**
     * Get all public properties and dehydrates them for storage
     *
     * @return MutableMap
     */

    public function getDehydratedAttributes()
    {
        $attributes = new MutableMap();
        $dehydrator = new Dehydrator();
        $this->prepare();

        foreach ($this->getAttributes() as $attribute => $value) {
            $type = $this->getFieldConfig($attribute, 'type');
            $default = $this->getFieldConfig($attribute, 'default');
            $value = $dehydrator->dehydrate($value, $type, $default);

            $attributes->set($attribute, $value);
        }

        return $attributes;
    }
}
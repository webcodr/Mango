<?php

namespace Mango\Persistence;

/**
 * Class Collection
 * @package Mango\Persistence
 */

class Collection
{
    private $name;
    private $mongoCollection;

    /**
     * Constructor
     *
     * @param $name
     * @param \MongoCollection $collection
     */

    public function __construct($name, \MongoCollection $collection)
    {
        $this->name = $name;
        $this->mongoCollection = $collection;
    }

    /**
     * Get collection name
     *
     * @return string
     */

    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Database object
     *
     * @return Database
     */

    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Execute a query on the collection
     *
     * @param array $query
     * @param $hydrationClassName
     * @return Cursor
     */

    public function where(array $query, $hydrationClassName)
    {
        return $this->wrapCursor(
            $this->mongoCollection->find($query),
            $hydrationClassName
        );
    }

    /**
     * Wrap a MongoCursor object
     *
     * @param \MongoCursor $cursor
     * @param $hydrationClassName
     * @return Cursor
     */

    public function wrapCursor(\MongoCursor $cursor, $hydrationClassName)
    {
        return new Cursor($cursor, $hydrationClassName, true);
    }

    /**
     * Create an index on the given field(s)
     *
     * @param $fields
     * @param array $options
     * @return bool
     */

    public function ensureIndex($fields, array $options = [])
    {
        return $this->mongoCollection->ensureIndex($fields, $options);
    }

    /**
     * Delete index of the given field(s)
     *
     * @param $fields
     * @return array
     */

    public function deleteIndex($fields)
    {
        return $this->mongoCollection->deleteIndex($fields);
    }

    /**
     * Save a document in the collection
     *
     * @param $document
     * @return array|bool
     */

    public function save($document)
    {
        return $this->mongoCollection->save($document);
    }

    /**
     * Remove a document from the collection
     *
     * @param $id
     * @return mixed
     */

    public function remove($id) {
        if (!$id instanceof \MongoId) {
            $id = new \MongoId($id);
        }

        return $this->mongoCollection->remove(['_id' => $id]);
    }
}
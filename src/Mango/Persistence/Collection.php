<?php

namespace Mango\Persistence;

/**
 * Class Collection
 * @package Mango\Persistence
 */

class Collection
{
    private $connection;
    private $name;
    private $database;

    /**
     * Constructor
     *
     * @param Connection $connection
     * @param $name
     * @param Database $db
     */

    public function __construct(Connection $connection, $name, Database $db)
    {
        $this->connection = $connection;
        $this->name = $name;
        $this->database = $db;
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
     * Get MongoCollection object of the collection object
     *
     * @return \MongoCollection
     */

    private function getMongoCollection()
    {
        return $this->database->getMongoDB()->selectCollection($this->name);
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
            $this->getMongoCollection()->find($query),
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
        return $this->getMongoCollection()->ensureIndex($fields, $options);
    }

    /**
     * Delete index of the given field(s)
     *
     * @param $fields
     * @return array
     */

    public function deleteIndex($fields)
    {
        return $this->getMongoCollection()->deleteIndex($fields);
    }

    /**
     * Save a document in the collection
     *
     * @param $document
     * @return array|bool
     */

    public function save($document)
    {
        return $this->getMongoCollection()->save($document);
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

        return $this->getMongoCollection()->remove(['_id' => $id]);
    }
}
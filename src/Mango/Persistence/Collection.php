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

    public function getMongoCollection()
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
     * Save a document in the collection
     *
     * @param $document
     */

    public function save($document)
    {
        $this->getMongoCollection()->save($document);
    }

    /**
     * Remove a document from the collection
     *
     * @param \MongoId $id
     */

    public function remove($id) {
        if (!$id instanceof \MongoId) {
            $id = new \MongoId($id);
        }

        $this->getMongoCollection()->remove(['_id' => $id]);
    }
}
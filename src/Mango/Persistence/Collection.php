<?php

namespace Mango\Persistence;

class Collection {
    private $connection;
    private $name;
    private $database;

    public function __construct(Connection $connection, $name, Database $db)
    {
        $this->connection = $connection;
        $this->name = $name;
        $this->database = $db;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMongoCollection()
    {
        return $this->database->getMongoDB()->selectCollection($this->name);
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function save($document)
    {
        $this->getMongoCollection()->save($document);
    }

    public function remove(\MongoId $id) {
        $this->getMongoCollection()->remove(['_id' => $id]);
    }
}
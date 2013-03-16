<?php

namespace Mango\Persistence;

/**
 * Class Database
 * @package Mango\Persistence
 */

class Database
{
    private $connection;
    private $name;

    /**
     * Constructor
     *
     * @param Connection $connection
     * @param $name
     */

    public function __construct(Connection $connection, $name)
    {
        $this->connection = $connection;
        $this->name = $name;
    }

    /**
     * Get the database name
     *
     * @return string
     */

    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the MongoDB object from the database
     *
     * @return \MongoDB
     */

    public function getMongoDB()
    {
        return $this->connection->getMongo()->selectDB($this->name);
    }

    /**
     * Select a collection on the database
     *
     * @param $name
     * @return Collection
     */

    public function selectCollection($name)
    {
        return new Collection($this->connection, $name, $this);
    }
}
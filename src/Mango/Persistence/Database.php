<?php

namespace Mango\Persistence;

class Database
{
    private $connection;
    private $name;

    public function __construct(Connection $connection, $name)
    {
        $this->connection = $connection;
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMongoDB()
    {
        return $this->connection->getMongo()->selectDB($this->name);
    }

    public function selectCollection($name)
    {
        return new Collection($this->connection, $name, $this);
    }
}
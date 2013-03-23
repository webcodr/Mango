<?php

namespace Mango\Persistence;

/**
 * Class Database
 * @package Mango\Persistence
 */

class Database
{
    private $mongoDb;
    private $name;

    /**
     * Constructor
     *
     * @param $name
     * * @param \MongoDB $mongoDb
     */

    public function __construct($name, \MongoDB $mongoDb)
    {
        $this->name = $name;
        $this->mongoDb = $mongoDb;
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
     * Select a collection on the database
     *
     * @param $name
     * @return Collection
     */

    public function selectCollection($name)
    {
        return new Collection(
            $name,
            $this->mongoDb->selectCollection($name)
        );
    }
}
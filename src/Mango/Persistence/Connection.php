<?php

namespace Mango\Persistence;

/**
 * Class Connection
 * @package Mango\Persistence
 */

class Connection {
    private $mongo;
    private $server;
    private $options;
    private $database;

    /**
     * Constructor
     *
     * @param $server
     * @param array $options
     */

    public function __construct($server, $options = array())
    {
        if ($server instanceof \Mongo || $server instanceof \MongoClient) {
            $this->mongo = $server;
        } elseif ($server !== null) {
            $this->server = $server;
            $this->options = $options;
        }
    }

    /**
     * Initiziale the MongoDB connection
     *
     * @param bool $reinitialize
     */

    public function initialize($reinitialize = false)
    {
        if ($reinitialize === true || $this->mongo === null) {
            $server  = $this->server;
            $options = $this->options;

            if (version_compare(phpversion('mongo'), '1.3.0', '<')) {
                $mongo = new \Mongo($server ?: 'mongodb://localhost:27017', $options);
            } else {
                $mongo = new \MongoClient($server ?: 'mongodb://localhost:27017', $options);
            }

            $this->mongo = $mongo;
        }
    }

    /**
     * Check if a connection to MongoDB is established
     *
     * @return bool
     */

    public function isConnected()
    {
        return $this->mongo !== null
            && (
                $this->mongo instanceof \Mongo
                || $this->mongo instanceof \MongoClient
            )
            && $this->mongo->connected;
    }

    /**
     * Connect to MongoDB
     */

    public function connect()
    {
        $this->initialize();
    }

    /**
     * Disconnect from MongoDB
     */

    public function disconnect()
    {
        $this->initialize();
        $this->mongo->close();
    }

    /**
     * Select a database
     *
     * @param $db
     * @return Database
     */

    public function selectDatabase($db)
    {
        $this->initialize();
        $database = $this->wrapDatabase($db);
        $this->database = $database;

        return $database;
    }

    /**
     * Get Database object
     *
     * @return Database
     * @throws \Exception
     */

    public function getDatabase()
    {
        if (!$this->database instanceof Database) {
            throw new \Exception('No database selected.');
        }

        return $this->database;
    }

    /**
     * Select a collection on the database
     *
     * @param $collection
     * @return Collection
     */

    public function selectCollection($collection)
    {
        $this->initialize();
        return $this->getDatabase()->selectCollection($collection);
    }

    /**
     * Wrap a database
     *
     * @param $database
     * @return Database
     */

    public function wrapDatabase($database)
    {
        return new Database(
            $database,
            $this->mongo->selectDB($database)
        );
    }
}
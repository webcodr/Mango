<?php

namespace Mango\Persistence;

class Connection {
    private $mongo;
    private $server;
    private $options;

    public function __construct($server, $options = array())
    {
        if ($server instanceof \Mongo || $server instanceof \MongoClient) {
            $this->mongo = $server;
        } elseif ($server !== null) {
            $this->server = $server;
            $this->options = $options;
        }
    }

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

    public function getMongo()
    {
        return $this->mongo;
    }

    public function isConnected()
    {
        return $this->mongo !== null && $this->mongo instanceof \Mongo && $this->mongo->connected;
    }

    public function connect()
    {
        $this->initialize();
    }

    public function disconnect()
    {
        $this->initialize();
        $this->mongo->close();
    }

    public function selectDatabase($db)
    {
        $this->initialize();
        return $this->wrapDatabase($db);
    }

    public function selectCollection($db, $collection)
    {
        $this->initialize();
        return $this->selectDatabase($db)->selectCollection($collection);
    }

    public function wrapDatabase($db)
    {
        return new Database($this, $db);
    }
}
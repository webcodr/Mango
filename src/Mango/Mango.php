<?php

namespace Mango;

use Mango\Persistence\Connection;
use Mango\Exception\ConnectionException;

class Mango
{
    private $uri;
    private $connection;
    private $database;

    public function __construct($uri)
    {
        if (parse_url($uri, PHP_URL_SCHEME) !== 'mongodb') {
            throw new ConnectionException('Please set a valid MongoDB URI.');
        }

        $this->connect($uri);

        $database = basename($uri);

        if (!empty($database)) {
            $this->database = $this->getConnection()->selectDatabase($database);
        }
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    private function connect($uri)
    {
        $connection = new Connection($uri);
        $connection->connect();
        $this->connection = $connection;
    }

    public function disconnect() {
        if ($this->connection->isConnected()) {
            $this->connection->disconnect();
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
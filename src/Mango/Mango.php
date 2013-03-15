<?php

namespace Mango;

use Mango\Persistence\Connection;
use Mango\Exception\ConnectionException;

class Mango
{
    private $uri;
    private $connection;

    public function __construct($uri, $autoConnect = true)
    {
        if (parse_url($uri, PHP_URL_SCHEME) !== 'mongodb') {
            throw new ConnectionException('Please set a valid MongoDB URI.');
        }

        $this->uri = $uri;

        if ($autoConnect === true) {
            $this->connect();
        }
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function connect()
    {
        $connection = new Connection($this->uri);
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
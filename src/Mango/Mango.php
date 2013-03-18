<?php

namespace Mango;

use Mango\Persistence\Connection;
use Mango\Exception\ConnectionException;

/**
 * Class Mango
 * @package Mango
 */

class Mango
{
    private $connection;
    private static $documentManager;

    private $database;

    /**
     * Constructor
     *
     * @param $uri
     * @throws  \Mango\Exception\ConnectionException
     */

    public function __construct($uri)
    {
        // check for valid mongo uri
        if (parse_url($uri, PHP_URL_SCHEME) !== 'mongodb') {
            throw new ConnectionException('Please set a valid MongoDB URI.');
        }

        $this->connect($uri);

        $database = basename($uri);

        // select the database
        if (!empty($database)) {
            $this->database = $this->getConnection()->selectDatabase($database);
        }
    }

    /**
     * Destructor
     */

    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * @param DocumentManager $documentManager
     */

    public static function setDocumentManager(DocumentManager $documentManager)
    {
        self::$documentManager = $documentManager;
    }

    /**
     * @return mixed
     * @throws \Exception
     */

    public static function getDocumentManager()
    {
        if (!self::$documentManager instanceof DocumentManager) {
            throw new \Exception('Missing Document Manager!');
        }

        return self::$documentManager;
    }

    /**
     * Connect to MongoDB
     *
     * @param $uri
     */

    private function connect($uri)
    {
        $connection = new Connection($uri);
        $connection->connect();
        $this->connection = $connection;
    }

    /**
     * Disconnect
     */

    public function disconnect() {
        if ($this->connection->isConnected()) {
            $this->connection->disconnect();
        }
    }

    /**
     * Get connection object
     *
     * @return \Mango\Persistence\Connection
     */

    public function getConnection()
    {
        return $this->connection;
    }
}
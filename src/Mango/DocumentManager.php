<?php

namespace Mango;

use Collection\MutableMap;

/**
 * Class DocumentManager
 * @package Mango
 */

class DocumentManager
{
    private $connection;

    /**
     * Constructor
     *
     * @param Mango $mango
     */

    public function __construct(Mango $mango)
    {
        $this->connection = $mango->getConnection();
    }

    /**
     * Store a document
     *
     * @param DocumentInterface $document
     */

    public function store(DocumentInterface $document)
    {
        $collection = $this->connection->selectCollection($document::getCollectionName());
        $data = $document->getProperties()->getArray();
        $collection->save($data);
    }

    /**
     * Remove a document
     *
     * @param DocumentInterface $document
     */

    public function remove(DocumentInterface $document)
    {
        $collection = $this->connection->selectCollection($document::getCollectionName());
        $collection->remove($document->_id);
    }

    /**
     * Execute a query on a collection
     *
     * @param $collection
     * @param array $query
     * @param $hydrationClassName
     * @return \Mango\Persistence\Cursor
     */

    public function where($collection, array $query, $hydrationClassName)
    {
        $collection = $this->connection->selectCollection($collection);

        return $collection->where($query, $hydrationClassName);
    }
}
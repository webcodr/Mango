<?php

namespace Mango;

use Collection\MutableMap;

class DocumentManager
{
    private $connection;

    public function __construct(Mango $mango)
    {
        $this->connection = $mango->getConnection();
    }

    public function store(DocumentInterface $document)
    {
        $collection = $this->connection->selectCollection($document::getCollectionName());
        $data = $document->getProperties()->getArray();
        $collection->save($data);
    }

    public function remove(DocumentInterface $document)
    {
        $collection = $this->connection->selectCollection($document::getCollectionName());
        $collection->remove($document->_id);
    }

    public function where($collection, array $query, $hydrationClassName)
    {
        $collection = $this->connection->selectCollection($collection);

        return $collection->where($query, $hydrationClassName);
    }
}
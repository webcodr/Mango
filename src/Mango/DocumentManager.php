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

    protected function getCollection($database, $collection)
    {
        return $this->connection->selectCollection($database, $collection);
    }

    public function store(DocumentInterface $document)
    {
        if (!isset($document->_id) || !$document->_id instanceof \MongoId) {
            throw new \InvalidArgumentException('Document does not contain a valid MongoID.');
        }

        $collection = $this->getCollection(
            $document->getDatabase(),
            $document->getCollectionName()
        );

        $data = $document->getProperties()->getArray();
        $collection->save($data);
    }

    public function remove(DocumentInterface $document)
    {
        if (!isset($document->_id) || !$document->_id instanceof \MongoId) {
            throw new \InvalidArgumentException('Document does not contain a valid MongoID.');
        }

        $collection = $this->getCollection(
            $document->getDatabase(),
            $document->getCollectionName()
        );

        $collection->remove($document->_id);
    }
}
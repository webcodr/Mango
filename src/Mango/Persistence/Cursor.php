<?php

namespace Mango\Persistence;

use Collection\MutableMap;

class Cursor implements \IteratorAggregate
{
    private $cursor;
    private $hydrate = true;
    private $hydrationClassName;

    public function __construct(\MongoCursor $cursor, $hydrationClassName, $hydrate = true)
    {
        $this->cursor = $cursor;
        $this->hydrationClassName = $hydrationClassName;
        $this->hydrate = $hydrate;
    }

    public function __call($method, $arguments)
    {
        $object = $this;

        if (method_exists(new MutableMap(), $method)) {
            $object = $this->getDocuments();
        }

        return call_user_func_array(
            [$object, $method],
            $arguments
        );
    }

    public function count()
    {
        return $this->cursor->count();
    }

    public function limit($limit)
    {
        $this->cursor->limit($limit);

        return $this;
    }

    public function skip($skip)
    {
        $this->cursor->skip($skip);

        return $this;
    }

    private function getDocuments()
    {
        $data = [];

        foreach ($this->cursor as $document) {
            if ($this->hydrate === true) {
                $doc = new $this->hydrationClassName();
                $doc->hydrate($document);
                $data[] = $doc;
            } else {
                $data[] = $document;
            }
        }

        return new MutableMap($data);
    }

    public function getIterator()
    {
        return $this->getDocuments()->getIterator();
    }
}
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

    public function getIterator()
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

        $map = new MutableMap($data);

        return $map->getIterator();
    }
}
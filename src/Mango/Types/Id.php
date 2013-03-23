<?php

namespace Mango\Types;

class Id implements TypeInterface
{
    private $value;

    public function __construct($value = null)
    {
        if ($value instanceof \MongoId) {
            $value = $value->__toString();
        }

        $this->value = $value;
    }

    public function getMongoType()
    {
        return new \MongoId($this->value);
    }

    public function getValue()
    {
        return $this->value;
    }
}
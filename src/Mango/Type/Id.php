<?php

namespace Mango\Type;

class Id implements TypeInterface
{
    private $value;

    public function __construct($value = null)
    {
        if (!$value instanceof \MongoId) {
            $value = new \MongoId($value);
        }

        $this->value = $value->__toString();;
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
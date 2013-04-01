<?php

namespace Mango\Type;

class String implements TypeInterface
{
    private $value;

    public function __construct($value = null)
    {
        $this->value = $value;
    }

    public function getMongoType()
    {
        return $this->value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
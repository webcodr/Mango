<?php

namespace Mango\Type;

interface TypeInterface
{
    public function __construct($value = null);
    public function getMongoType();
    public function getValue();
}
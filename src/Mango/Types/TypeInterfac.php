<?php

namespace Mango\Types;

interface TypeInterface
{
    public function __construct($value = null);
    public function getMongoType();
    public function getValue();
}
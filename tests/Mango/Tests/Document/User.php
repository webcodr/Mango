<?php

namespace Mango\Tests\Document;

use Mango\Document;
use Mango\DocumentInterface;

class User implements DocumentInterface
{
    use Document;

    public $name;
}
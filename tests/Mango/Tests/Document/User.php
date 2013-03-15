<?php

namespace Mango\Tests\Document;

use Mango\Document as MangoDocument;

class User extends MangoDocument
{
    public $name;

    public function __construct()
    {
        parent::__construct('mango');
    }
}
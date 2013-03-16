<?php

namespace Mango\Tests\Document;

use Mango\Document;
use Mango\DocumentInterface;

class User implements DocumentInterface
{
    use Document;

    public $created_at;
    public $updated_at;
    public $name;

    private function addFields()
    {
        $this->addField(
            'name',
            []
        );

        $this->addField(
            'created_at',
            [
                'type' => 'DateTime',
                'index' => true,
                'default' => 'now'
            ]
        );

        $this->addField(
            'updated_at',
            [
                'type' => 'DateTime',
                'index' => true,
                'default' => 'now'
            ]
        );
    }
}
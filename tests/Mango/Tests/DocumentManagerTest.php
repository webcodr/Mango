<?php

namespace Mango\Tests;

use Mango\Mango;
use Mango\DocumentManager;
use Mango\Tests\Document\User;

class DocumentManagerTest extends \PHPUnit_Framework_TestCase {
    private function getConnection()
    {
        return new Mango('mongodb://localhost:27017');
    }

    public function testStore()
    {
        $mango = $this->getConnection();
        $dm = new DocumentManager($mango);
        $document = new User();
        $document->name = 'Foo Bar';
        $dm->store($document);
        $dm->remove($document);
    }
}
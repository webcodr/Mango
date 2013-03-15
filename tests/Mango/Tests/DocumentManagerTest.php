<?php

namespace Mango\Test;

use Mango\Mango;
use Mango\DocumentManager;
use Mango\Document;

class DocumentManagerTest extends \PHPUnit_Framework_TestCase {
    private function getConnection()
    {
        return new Mango('mongodb://localhost:27017');
    }

    public function testStore()
    {
        $mango = $this->getConnection();
        $dm = new DocumentManager($mango);
        $document = new Document('mangotest', 'test');
        $dm->store($document);
        $dm->remove($document);
    }
}
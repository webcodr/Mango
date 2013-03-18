<?php

namespace Mango\Tests;

use Mango\Mango;
use Mango\DocumentManager;
use Mango\Tests\Document\User;

class DocumentManagerTest extends \PHPUnit_Framework_TestCase {
    private function getConnection()
    {
        $mango = new Mango('mongodb://localhost:27017/mango-unittests');
        $dm = new DocumentManager($mango);

        return $mango;
    }

    public function testStore()
    {
        $mango = $this->getConnection();
        $document = new User();
        $document->name = 'Foo Bar';
        $document->store();
        $query = ['name' => 'Foo Bar'];
        self::assertEquals(1, $document::where($query)->count());

        $document->remove();
        self::assertEquals(0, $document::where($query)->count());
    }

    public function testQuery()
    {
        $mango = $this->getConnection();
        $document = new User();
        $document->name = 'Foo Bar';
        $document->store();
        $query = ['name' => 'Foo Bar'];

        $user = $document::where($query)->head();
        self::assertEquals($document->getDehydratedProperties(), $user->getDehydratedProperties());

        $document->remove();
        self::assertEquals(0, $document::where($query)->count());
    }

    public function testHydration()
    {
        $mango = $this->getConnection();
        $document = new User();
        $document->name = 'Foo Bar';
        $document->store();
        $query = ['name' => 'Foo Bar'];

        $user = $document::where($query)->head();
        $user->updated_at = new \DateTime('+4 hours');
        $user->store();
        self::assertEquals(1, $document::where($query)->count());

        $document->remove();
        self::assertEquals(0, $document::where($query)->count());
    }
}
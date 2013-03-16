<?php

namespace Mango\Tests;

use Mango\Mango;
use Mango\DocumentManager;
use Mango\Tests\Document\User;

class DocumentManagerTest extends \PHPUnit_Framework_TestCase {
    private function getConnection()
    {
        return new Mango('mongodb://localhost:27017/mango-unittests');
    }

    public function testStore()
    {
        $mango = $this->getConnection();
        $dm = new DocumentManager($mango);
        $document = new User();
        $document->name = 'Foo Bar';
        $dm->store($document);
        $query = ['name' => 'Foo Bar'];
        self::assertEquals(1, $document::where($dm, $query)->count());

        $dm->remove($document);
        self::assertEquals(0, $document::where($dm, $query)->count());
    }

    public function testQuery()
    {
        $mango = $this->getConnection();
        $dm = new DocumentManager($mango);
        $document = new User();
        $document->name = 'Foo Bar';
        $dm->store($document);
        $query = ['name' => 'Foo Bar'];

        $user = $document::where($dm, $query)->head();
        self::assertEquals($document->getProperties(), $user->getProperties());

        $dm->remove($document);
        self::assertEquals(0, $document::where($dm, $query)->count());
    }

    public function testHydration()
    {
        $mango = $this->getConnection();
        $dm = new DocumentManager($mango);
        $document = new User();
        $document->name = 'Foo Bar';
        $dm->store($document);
        $query = ['name' => 'Foo Bar'];

        $user = $document::where($dm, $query)->head();
        $user->updated_at = new \DateTime('+4 hours');
        $dm->store($user);
        self::assertEquals(1, $document::where($dm, $query)->count());

        $dm->remove($document);
        self::assertEquals(0, $document::where($dm, $query)->count());
    }
}
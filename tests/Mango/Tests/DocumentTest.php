<?php

namespace Mango\Tests;

use Mango\Mango;
use Mango\DocumentManager;
use Mango\Tests\Document\User;

class DocumentTest extends \PHPUnit_Framework_TestCase
{
    private function getConnection()
    {
        $mango = new Mango('mongodb://localhost:27017/mango-unittests');
        $dm = new DocumentManager($mango);

        return $mango;
    }

    public function tearDown()
    {
        User::where([])->each(function($document) {
            $document->remove();
        });
    }

    public function testFind()
    {
        $this->getConnection();
        $ids = [];

        for ($i = 0; $i <= 3; $i++) {
            $user = new User();
            $user->name = "Test {$i}";
            $user->store();
            $ids[] = (string)$user->_id;
        }

        $result = call_user_func_array(['\Mango\Tests\Document\User', 'find'], $ids);

        self::assertEquals(4, $result->count());
    }
}
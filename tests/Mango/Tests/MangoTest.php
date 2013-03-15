<?php

namespace Mango\Tests;

use Mango\Mango;

class MangoTest extends \PHPUnit_Framework_TestCase
{
    private function getConnection()
    {
        return new Mango('mongodb://localhost:27017/mango-unittests');
    }

    public function testConnect()
    {
        $mango = $this->getConnection();

        self::assertTrue($mango->getConnection()->isConnected());
    }

    /**
     * @expectedException \Mango\Exception\ConnectionException
     */

    public function testConnectWithInvalidUri()
    {
        $mango = new Mango('fgdkijadsfsklfkjlds');
    }

    public function testDisconnect()
    {
        $mango = $this->getConnection();
        $mango->disconnect();

        self::assertFalse($mango->getConnection()->isConnected());
    }
}
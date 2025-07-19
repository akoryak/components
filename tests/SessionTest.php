<?php

namespace Akoryak\Components\Tests;

use Akoryak\Components\Cache;
use Akoryak\Components\Session;
use Akoryak\Components\Session\SessionException;
use stdClass;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

class SessionTest extends TestCase
{
    public function testInitWithPDO()
    {
        if (headers_sent($f,$l)) {
            die('PHPUnit for PHP7 sent headers before executing a unit test');
        }
        // Create a mock for PDOStatement
        // $stmtMock = $this->createMock(PDOStatement::class);
        // $stmtMock->method('fetch')->willReturn(['test_data']);

        // Create a mock for PDO
        $pdoMock = $this->createMock(PDO::class);
        // $pdoMock->method('query')->willReturn($stmtMock);

        // Call the init method with the PDO mock
        Session::init($pdoMock);

        $this->assertEquals('user', ini_get('session.save_handler'));
    }

    public function testInitWithCacheDriver()
    {
        if (headers_sent($f,$l)) {
            die('PHPUnit for PHP7 sent headers before executing a unit test');
        }

        $cacheDriverMock = $this->createMock(Cache::class);
        // $cacheDriverMock->method('get')->willReturn('12345');

        // Call the init method with the CacheDriver mock
        Session::init($cacheDriverMock);

        $this->assertEquals('user', ini_get('session.save_handler'));
    }

    public function testInitWithUnsupportedDriver()
    {
        // Call the init method with an unsupported driver
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('This handler is not implemented yet');
        
        Session::init(new stdClass());
    }
}

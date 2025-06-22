<?php

namespace Akoryak\Components\Session\Tests;

use PDO;
use PDOStatement;
use Akoryak\Components\Session\SessionHandlerDb;
use Akoryak\Components\Session\SessionHandlerDbLegacy;
use PHPUnit\Framework\TestCase;

class SessionHandlerDbTest extends TestCase
{
    private $pdoMock;
    private $sessionHandler;

    protected function setUp(): void
    {
        // Create a mock for PDO
        $this->pdoMock = $this->createMock(PDO::class);

        // Create an instance of SessionHandlerDb with the mock PDO
        if (PHP_MAJOR_VERSION <= 7) {
            $this->sessionHandler = new SessionHandlerDbLegacy($this->pdoMock);
        } else {
            $this->sessionHandler = new SessionHandlerDb($this->pdoMock);
        }  
    }

    public function testOpen()
    {
        $this->assertTrue($this->sessionHandler->open('/tmp', 'test_session'));
    }

    public function testClose()
    {
        $this->assertTrue($this->sessionHandler->close());
    }

    public function testRead()
    {
        $id = 'test_id';
        $data = 'test_data';

        // Create a mock for PDOStatement
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('fetch')
                 ->willReturn([$data]);

        // Set up the PDO mock to return the statement mock when query is called
        $this->pdoMock->method('query')
                      ->willReturn($stmtMock);

        $result = $this->sessionHandler->read($id);
        $this->assertEquals($data, $result);
    }

    public function testWrite()
    {
        $id = 'test_id';
        $data = 'test_data';

        // Set up the PDO mock to return the statement mock when exec is called
        $this->pdoMock->method('exec')
                      ->willReturn(1);

        $this->assertTrue($this->sessionHandler->write($id, $data));
    }

    public function testDestroy()
    {
        $id = 'test_id';

        // Set up the PDO mock to return true when exec is called
        $this->pdoMock->method('exec')
                      ->willReturn(1);

        $this->assertTrue($this->sessionHandler->destroy($id));
    }

    public function testGc()
    {
        $maxLifetime = 1440;

        // Set up the PDO mock to return true when exec is called
        $this->pdoMock->method('exec')
                      ->willReturn(1);

        $this->assertTrue($this->sessionHandler->gc($maxLifetime) === 1);
    }
}

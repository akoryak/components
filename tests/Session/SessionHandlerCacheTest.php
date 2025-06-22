<?php

namespace Akoryak\Components\Session\Tests;

use Akoryak\Components\Session\SessionHandlerCache;
use Akoryak\Components\Session\SessionHandlerCacheLegacy;
use Akoryak\Components\Cache;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class SessionHandlerCacheTest extends TestCase
{
    private MockObject $cacheDriver;
    private $sessionHandler;

    protected function setUp(): void
    {
        $this->cacheDriver = $this->createMock(Cache::class);
        if (PHP_MAJOR_VERSION <= 7) {
            $this->sessionHandler = new SessionHandlerCacheLegacy($this->cacheDriver, 3600);
        } else {
            $this->sessionHandler = new SessionHandlerCache($this->cacheDriver, 3600);
        }        
    }

    public function testOpen(): void
    {
        $this->assertTrue($this->sessionHandler->open('/tmp', 'session_name'));
    }

    public function testClose(): void
    {
        $this->assertTrue($this->sessionHandler->close());
    }

    public function testRead(): void
    {
        $sessionId = '12345';
        $expectedData = 'session_data';
        $this->cacheDriver->method('get')
            ->with('session_' . $sessionId)
            ->willReturn($expectedData);

        $this->assertEquals($expectedData, $this->sessionHandler->read($sessionId));
    }

    public function testWrite(): void
    {
        $sessionId = '12345';
        $data = 'session_data';
        $this->cacheDriver->method('set')
            ->with('session_' . $sessionId, $data, 3600)
            ->willReturn(true);

        $this->assertTrue($this->sessionHandler->write($sessionId, $data));
    }

    public function testDestroy(): void
    {
        $sessionId = '12345';
        $this->cacheDriver->method('delete')
            ->with('session_' . $sessionId)
            ->willReturn(true);

        $this->assertTrue($this->sessionHandler->destroy($sessionId));
    }

    public function testGc(): void
    {
        $this->assertEquals(1, $this->sessionHandler->gc(3600));
    }
}

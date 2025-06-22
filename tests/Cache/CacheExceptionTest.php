<?php

namespace Akoryak\Components\Cache\Tests;

use Akoryak\Components\Cache\CacheException;
use PHPUnit\Framework\TestCase;

class CacheExceptionTest extends TestCase
{
    public function testCacheExceptionCanBeInstantiated()
    {
        $exception = new CacheException();
        $this->assertInstanceOf(CacheException::class, $exception);
    }

    public function testCacheExceptionCanBeInstantiatedWithMessage()
    {
        $message = 'Test exception message';
        $exception = new CacheException($message);
        $this->assertInstanceOf(CacheException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
    }

    public function testCacheExceptionCanBeInstantiatedWithMessageAndCode()
    {
        $message = 'Test exception message';
        $code = 123;
        $exception = new CacheException($message, $code);
        $this->assertInstanceOf(CacheException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
    }

    public function testCacheExceptionCanBeInstantiatedWithMessageCodeAndPreviousException()
    {
        $message = 'Test exception message';
        $code = 123;
        $previousException = new \Exception('Previous exception message');
        $exception = new CacheException($message, $code, $previousException);
        $this->assertInstanceOf(CacheException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
        $this->assertSame($previousException, $exception->getPrevious());
    }
}

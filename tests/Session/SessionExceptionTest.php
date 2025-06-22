<?php

namespace Akoryak\Components\Session\Tests;

use Akoryak\Components\Session\SessionException;
use PHPUnit\Framework\TestCase;

class SessionExceptionTest extends TestCase
{
    public function testSessionExceptionCanBeInstantiated()
    {
        $exception = new SessionException();
        $this->assertInstanceOf(SessionException::class, $exception);
    }

    public function testSessionExceptionCanBeInstantiatedWithMessage()
    {
        $message = 'Test exception message';
        $exception = new SessionException($message);
        $this->assertInstanceOf(SessionException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
    }

    public function testSessionExceptionCanBeInstantiatedWithMessageAndCode()
    {
        $message = 'Test exception message';
        $code = 123;
        $exception = new SessionException($message, $code);
        $this->assertInstanceOf(SessionException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
    }

    public function testSessionExceptionCanBeInstantiatedWithMessageCodeAndPreviousException()
    {
        $message = 'Test exception message';
        $code = 123;
        $previousException = new \Exception('Previous exception message');
        $exception = new SessionException($message, $code, $previousException);
        $this->assertInstanceOf(SessionException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
        $this->assertSame($previousException, $exception->getPrevious());
    }
}

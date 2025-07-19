<?php

namespace Akoryak\Components\Session;

use Akoryak\Components\Cache;
use Akoryak\Components\Session\SessionHandlerInterface;
use Akoryak\Components\Session\SessionHandlerLegacyInterface;


class SessionHandlerCacheLegacy implements SessionHandlerInterface, SessionHandlerLegacyInterface
{
    private Cache $cacheDriver;
    private string $prefix = 'session_';
    private string $period;

    public function __construct(Cache $cacheDriver, $period) {
        $this->cacheDriver = $cacheDriver;
        $this->period = $period;
    }
    
	public function setSessionHandler() {
		session_set_save_handler(
			array($this, 'open'),
			array($this, 'close'),
			array($this, 'read'),
			array($this, 'write'),
			array($this, 'destroy'),
			array($this, 'gc')
		);
	}

    public function open(string $path, string $name): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string
    {
        $key = $this->prefix . $id;
        return $this->cacheDriver->get($key);
    }

    public function write(string $id, string $data): bool
    {
        $key = $this->prefix . $id;
        return $this->cacheDriver->set($key, $data, $this->period);
    }

    public function destroy(string $id): bool
    {
        $key = $this->prefix . $id;
        return $this->cacheDriver->delete($key);
    }

    public function gc(int $max_lifetime): int
    {
        return 1;
    }
}

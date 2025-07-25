<?php

namespace Akoryak\Components\Cache;

use Memcache;

class StrategyMemcache extends Memcache {
	
	public function connect(string $host, string $port) : bool {
		return (bool) $this->pconnect($host, $port);
	}
	
	public function touch(string $key, int $expiration): bool {
		// touch() is not supported by php extention Memcache
		$value = $this->get($key);
		return $this->set($key, $value, $expiration);
	}
	
	public function set(string $key, $value, int $expiration = 0): bool {
		return parent::set($key, $value, MEMCACHE_COMPRESSED, $expiration);
	}
}

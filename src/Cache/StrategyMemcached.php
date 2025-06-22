<?php

namespace Akoryak\Components\Cache;

use Memcached;

class StrategyMemcached extends Memcached implements StrategyInterface {
	
	public function connect(string $host, string $port) : bool {
		// Memcached::OPT_COMPRESSION is set by defaulf for all values longer then XXX bytes, where XXX depends on PHP version
		return $this->addServer($host, $port);
	}
}

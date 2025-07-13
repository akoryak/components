<?php

namespace Akoryak\Components;

/***
EXAMPLES:

use Akoryak\Components\Cache;

$cache = new Cache($host, $port);
$data = $cache->get('_some_key_');
if (empty($data)) {
	$data = _generate_new_data_();
	$cache->set('_some_key_', $data, $period);
}

$cache = new Cache($host, $port, '_prefix_');
$data = $cache->callable(
	'_this_is_a_key2_',
	function () use ($something) {
		return _generate_new_data_($something);
	},
	3600
);

***/
 
use Akoryak\Components\Cache\CacheException;
use Akoryak\Components\Cache\StrategyInterface;
use Akoryak\Components\Cache\StrategyMemcache;
use Akoryak\Components\Cache\StrategyMemcached;

class Cache {

    private StrategyInterface $strategy;

	public function __construct(string $host = 'localhost', int $port = 11211)
    {
		if ( class_exists( 'Memcache' )) {
			$this->strategy = new StrategyMemcache();
		} elseif ( class_exists( 'Memcached' )) {
			$this->strategy = new StrategyMemcached();
		} else {
			throw new CacheException('Neither Memcache nor Memcached PHP extention is installed.');
		}
		
		$this->strategy->connect($host, $port);
	}

    public function set(string $key, string $value, int $period = 0): bool
    {
		return $this->strategy->set($key, $value, $period);
	}

    public function get(string $key)
    {
		return $this->strategy->get($key);
	}

	public function delete(string $key): bool
	{
		return $this->strategy->delete($key);
	}

	public function memoize($key, callable $function, $period = 0)
	{
		$value = $this->get($key);
		if ($value === false) {
			$value = $function();
			$this->set($key, $value, $period);
		}

		return $value;
	}

    public function __call($function, $arguments)
    {
		return $this->strategy->$function($arguments);
    }
}

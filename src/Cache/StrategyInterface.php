<?php

namespace Akoryak\Components\Cache;

interface StrategyInterface {
	
	public function connect(string $host, string $port) : bool;

	/**
	 * Extend expiration time for already existing value
	 */
	public function touch(string $key, int $expiration): bool;
	
	public function get(string $key);
	
	public function set(string $key, $value, int $expiration = 0): bool;

	public function delete(string $key): bool;
}

<?php

namespace Akoryak\Components;

/***
EXAMPLES:

use Akoryak\Components\Cache;
use Akoryak\Components\Session;

$cacheDriver = new Cache($host, $port);
Session::init($cacheDriver);

***/

use Akoryak\Components\Cache;
use PDO;
use Akoryak\Components\Session\SessionException;
use Akoryak\Components\Session\SessionHandlerDb;
use Akoryak\Components\Session\SessionHandlerDbLegacy;
use Akoryak\Components\Session\SessionHandlerCache;
use Akoryak\Components\Session\SessionHandlerCacheLegacy;

class Session {

	/**
	 * @param PDO|Cache $driver
	 */
	// public static function init(PDO|Cache $driver): void
	public static function init($driver): void
    {
		if ($driver instanceof PDO) {
			$sessionHandler = self::initPdo($driver);
		} else if ($driver instanceof Cache) {
			$period = session_cache_expire() * 60; // in minutes * 60 = in seconds
			if (PHP_MAJOR_VERSION <= 7) {
				$sessionHandler = new SessionHandlerCacheLegacy($driver, $period);
			} else {
				$sessionHandler = new SessionHandlerCache($driver, $period);
			}
			session_set_save_handler($sessionHandler);
		} else {
			throw new SessionException('This handler is not implemented yet');
		}
	}

	public static function initPdo(PDO $driver): void
    {
		if (PHP_MAJOR_VERSION <= 7) {
			$sessionHandler = new SessionHandlerDbLegacy($driver);
			session_set_save_handler(
				array('Component_Session', 'open'),
				array('Component_Session', 'close'),
				array('Component_Session', 'read'),
				array('Component_Session', 'write'),
				array('Component_Session', 'destroy'),
				array('Component_Session', 'gc')
			);
		} else {
			$sessionHandler = new SessionHandlerDb($driver);
			session_set_save_handler($sessionHandler);
		}
	}

}

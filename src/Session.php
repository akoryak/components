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
use Akoryak\Components\Session\SessionHandlerLegacyInterface;

class Session {

	/**
	 * @param PDO|Cache $driver
	 */
	// public static function init(PDO|Cache $driver): void
	public static function init($driver): void
    {
		$sessionHandler = null;
		if ($driver instanceof PDO) {
			if (PHP_MAJOR_VERSION <= 7) {
				$sessionHandler = new SessionHandlerDbLegacy($driver);
			} else {
				$sessionHandler = new SessionHandlerDb($driver);
			}
		} elseif ($driver instanceof Cache) {
			$period = session_cache_expire() * 60; // in minutes * 60 = in seconds
			if (PHP_MAJOR_VERSION <= 7) {
				$sessionHandler = new SessionHandlerCacheLegacy($driver, $period);
			} else {
				$sessionHandler = new SessionHandlerCache($driver, $period);
			}
		}

		if (!empty($sessionHandler)) {
			$sessionHandler->setSessionHandler();
		} else {
			throw new SessionException('This handler is not implemented yet');
		}
	}
}

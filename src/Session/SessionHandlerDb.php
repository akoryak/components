<?php

namespace Akoryak\Components\Session;


/***
	CREATE TABLE `sessions` (
		`id` varchar(60) NOT NULL,
		`data` text NOT NULL,
		`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		-- `updated_at` int(10) unsigned NOT NULL,
		`user_id` int(10) unsigned NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8; # InnoDB fares a lot better under read conditions because it only locks at the row level rather than MyISAM which locks at the table level


	// init DB session handler
	Component_Session::init();

***/

use PDO;
use Akoryak\Components\Session\SessionHandlerInterface;
use SessionHandlerInterface as BuiltInInterface;


class SessionHandlerDb implements BuiltInInterface, SessionHandlerInterface
{
    private $database;
    private $table;
    private static $sData;

    public function __construct(PDO $database, string $tableName = 'session')
    {
        $this->database = $database;
        $this->table = $tableName;
    }

    public function setSessionHandler()
    {
		session_set_save_handler($this, true);
	}

    public function open(string $path, string $name): bool
    {
		// dump('open');
		return true;
	}

    public function close(): bool
    {
		// dump('close');
		return true;
	}

    public function read(string $id): string|false
    {
		// dump('read');
		try {
			$statment = $this->database->query("
				SELECT data FROM `{$this->table}` WHERE id = '{$id}'
			");
			$row = $statment->fetch(PDO::FETCH_NUM);
			self::$sData = $row[0] ?? '';
		} catch (\Throwable $e) {
			throw new SessionException("Database table {$this->table} has not been created.");
		}
		
		return self::$sData;
	}

    public function write(string $id, string $data): bool
    {
		// dump('write1');
		if (self::$sData == $data) {
			return true;
		}
		// dump('write2');

		try {
			return (bool) $this->database->exec('
				INSERT INTO `' . $this->table .'` (`id`, `data`) VALUES( ?, ? )
				ON DUPLICATE KEY UPDATE `data` = ?',
				array($id, $data, $data)
			);
		} catch (\Throwable $e) {
			throw new SessionException('Database table {$this->table} has not been created.');
		}
	}

    public function destroy(string $id): bool
    {
		// dump('destroy');
        return (bool) $this->database->exec(
			"DELETE FROM `{$this->table}` WHERE `id` = ?", array($id)
		);		
	}

	/**
	 * Garbage Collector. This is executed when the session garbage collector is executed
	 *
	 * @param int $maxlifetime Max session lifetime (sec.)
	 * @return bool
	 * @see session.gc_divisor	  100
	 * @see session.gc_maxlifetime 1440
	 * @see session.gc_probability	1
	 * @usage execution rate 1/100
	 *		(session.gc_probability/session.gc_divisor)
	 */
    public function gc(int $max_lifetime): int|false
    {
		// dump('gc');
		$time = time() - (int) $max_lifetime;
		$time = date('Y-m-d H:i:s', $time);
		return $this->database->exec("DELETE FROM `{$this->table}` WHERE `updated_at` < ?", array($time));
	}
}

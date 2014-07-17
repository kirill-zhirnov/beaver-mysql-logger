<?php

namespace components\app;
use KZ\db;

/**
 * Class Registry
 * @package app
 *
 * Following properties are available via magic methods (get/set):
 * @property \PDO $mysql
 *
 */
class Registry extends \KZ\app\Registry
{
	/**
	 * @return \PDO
	 */
	public function getMysql()
	{
		$mysql = $this->getConnectionStorage()->getByType(db\interfaces\ConnectionStorage::MYSQL);

		if (!$mysql)
			return null;

		return $mysql[array_keys($mysql)[0]];
	}
} 
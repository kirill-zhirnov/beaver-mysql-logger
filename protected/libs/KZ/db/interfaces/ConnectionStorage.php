<?php

namespace KZ\db\interfaces;

/**
 * This component is needed to store PDO connections. We can label connection with
 * special label or type. Also we can mark connection as default connection.
 *
 * Interface ConnectionStorage
 * @package KZ\db\interfaces
 */
interface ConnectionStorage
{
	const MYSQL = 'mysql';

	const SQLITE = 'sqlite';

	/**
	 * @param \PDO $connection
	 * @param $type - type of connection: mysql, sqlite, etc
	 * @param string $name - connection name. Null by default.
	 * @param bool $isDefault
	 * @return string - Returns connection name.
	 */
	public function add(\PDO $connection, $type, $name = null, $isDefault = false);

	/**
	 * @param $type - type of connection: mysql, sqlite, etc
	 * @return \PDO[]
	 */
	public function getByType($type);

	/**
	 * @param $name - connection name.
	 * @return \PDO
	 */
	public function getByName($name);

	/**
	 * @return \PDO - default connection
	 */
	public function getDefault();
} 
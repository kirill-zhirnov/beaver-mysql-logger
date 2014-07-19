<?php

namespace models;
use KZ\app\interfaces as appInterfaces;

abstract class ExecDbQuery
{
	const DB_MYSQL = 'mysql';

	/**
	 * @var appInterfaces\Registry
	 */
	protected $registry;

	/**
	 * @var string Database name
	 */
	protected $dbName;

	/**
	 * @var \PDO
	 */
	protected $connection;

	protected function setupConnection()
	{
		if (!$this->registry || !$this->dbName)
			throw new \RuntimeException('You must setup registry and dbName before calling this method!');

		if ($this->dbName == self::DB_MYSQL) {
			$this->connection = $this->registry->getMysql();
			return;
		}

		$credentials = new \tables\MysqlCredentials();
		$row = $credentials->findMysqlCredentials();

		if (!$row)
			throw new \RuntimeException('Credentials is empty');

		$row['mysql_dsn'] = preg_replace('#dbname=([\w]+)(?=$|\W)#i', 'dbname=' . $this->dbName, $row['mysql_dsn']);

		$this->connection = $credentials->createConnectionByRow($row);

		if (!$this->connection)
			throw new \RuntimeException('Cannot create connection!');
	}
}
<?php

namespace KZ\app;
use KZ\db;

class Kit implements interfaces\Kit
{
	protected $config;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * Create connectionStorage and fill it with PDO objects.
	 *
	 * @return \KZ\db\interfaces\ConnectionStorage
	 */
	public function makeConnectionStorage()
	{
		$db = new \PDO($this->config['db']['dsn'], $this->config['db']['username'], $this->config['db']['password'], $this->config['db']['options']);
		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		//fixme: вынести класс в конфиг
		$connectionStorage = new db\ConnectionStorage();
		$connectionStorage->add($db, db\interfaces\ConnectionStorage::SQLITE, 'db', true);

		//select mysql connections:
		//fixme: re-write on models usage and test it
		$result = $db->query('select * from mysql');
		foreach ($result as $row) {
			$options = ($row['mysql_options']) ? json_decode($row['mysql_options'], true) : [];
			$db = new \PDO($row['mysql_dsn'], $row['mysql_username'], $row['mysql_password'], $options);
			$db->query('set names utf8');

			$connectionStorage->add($db, db\interfaces\ConnectionStorage::MYSQL, null, false);
		}

		return $connectionStorage;
	}
} 
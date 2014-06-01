<?php

namespace components\app;
use KZ\db;

class Kit extends \KZ\app\Kit
{
	public function makeConnectionStorage()
	{
		$connectionStorage = parent::makeConnectionStorage();

		$this->makeMysqlConnections($connectionStorage);

		return $connectionStorage;
	}

	protected function makeMysqlConnections(db\interfaces\ConnectionStorage $connectionStorage)
	{
		//fixme: re-write on models usage and test it
		$result = $connectionStorage->getDefault()->query('select * from mysql');
		foreach ($result as $row) {
			$options = ($row['mysql_options']) ? json_decode($row['mysql_options'], true) : [];
			$db = new \PDO($row['mysql_dsn'], $row['mysql_username'], $row['mysql_password'], $options);
			$db->query('set names utf8');

			$connectionStorage->add($db, db\interfaces\ConnectionStorage::MYSQL, null, false);
		}
	}
} 
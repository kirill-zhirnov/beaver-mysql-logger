<?php

namespace models;
use KZ\db\table\SQLite;

class Mysql extends SQLite
{
	public function getMysqlConnection()
	{
		$row = $this->find();

		if (!$row)
			return null;

		try {
			$options = ($row['mysql_options']) ? json_decode($row['mysql_options'], true) : [];
			$pdo = new \PDO($row['mysql_dsn'], $row['mysql_username'], $row['mysql_password'], $options);
			$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$pdo->exec('set names utf8');
		} catch (\Exception $e) {
			return false;
		}

		return $pdo;
	}

	/**
	 * Return table name.
	 *
	 * @return string
	 */
	public function getTableName()
	{
		return 'mysql';
	}

	/**
	 * Primary keys fields.
	 *
	 * @return array
	 */
	public function getPk()
	{
		return ['mysql_id'];
	}
} 
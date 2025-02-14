<?php

namespace tables;
use KZ\db\table;
use PDO;

class MysqlCredentials extends table\SQLite
{
	public function createTable(): void
    {
        $stmt = $this->makeStmt("
            create table if not exists mysql_credentials (                
                mysql_id INTEGER PRIMARY KEY,
                mysql_dsn TEXT NULL default null,
                mysql_username TEXT NULL default null,
                mysql_password TEXT NULL default null,
                mysql_options TEXT NULL default null
            )
        ");
        $stmt->execute();
    }

	public function getMysqlConnection(): PDO|false|null
	{
        $this->createTable();
		$row = $this->findMysqlCredentials();

		if (!$row) {
            return null;
        }

		return self::createConnectionByRow($row);
	}

	public static function createConnectionByRow(array $row): PDO|false
	{
		try {
			$options = ($row['mysql_options']) ? json_decode($row['mysql_options'], true) : [];
			$pdo = new PDO($row['mysql_dsn'], $row['mysql_username'], $row['mysql_password'], $options);
			$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$pdo->exec('set names utf8');
		} catch (\Exception $e) {
			return false;
		}

		return $pdo;
	}

	public function findMysqlCredentials()
	{
		return $this->find();
	}

	/**
	 * Return table name.
	 *
	 * @return string
	 */
	public function getTableName()
	{
		return 'mysql_credentials';
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
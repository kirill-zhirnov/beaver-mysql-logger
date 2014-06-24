<?php

namespace KZ\db\interfaces;

interface TableModel
{
	/**
	 * @param \PDO $connection
	 */
	public function __construct(\PDO $connection = null);

	/**
	 * @param \PDO $pdo
	 * @return boolean
	 */
	public static function setDefaultConnection(\PDO $pdo);

	/**
	 * @return \PDO
	 */
	public static function getDefaultConnection();

	/**
	 * @return \PDO
	 */
	public function getConnection();

	/**
	 * @param \PDO $pdo
	 * @return $this
	 */
	public function setConnection(\PDO $pdo);

	/**
	 * Return table name.
	 *
	 * @return string
	 */
	public function getTableName();

	/**
	 * Primary keys fields.
	 *
	 * @return array
	 */
	public function getPk();

	/**
	 * Find one row by condition.
	 *
	 * @param array $parts
	 * @param array $params
	 * @internal param string $condition
	 * @return array|null
	 */
	public function find(array $parts = [], array $params = []);

	/**
	 * Find all rows by condition and params.
	 *
	 * @param array $parts
	 * @param array $params
	 * @internal param string $condition
	 * @return array
	 */
	public function findAll(array $parts = [], array $params = []);

	/**
	 * Find one row by primary key.
	 *
	 * @param array $pk
	 * @return array
	 */
	public function findByPk(array $pk);

	/**
	 * Extremely simple query builder.
	 *
	 * @param array $parts
	 * @return string
	 */
	public function buildSelectQuery(array $parts = []);

	/**
	 * @param $sql
	 * @param array $params
	 * @return \PDOStatement
	 */
	public function makeStmt($sql, array $params = []);

	/**
	 * @param \PDOStatement $stmt
	 * @param $params
	 * @return $this
	 */
	public function bindValues(\PDOStatement $stmt, $params);
} 
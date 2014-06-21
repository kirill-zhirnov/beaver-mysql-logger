<?php

namespace KZ\db\table;
use KZ\db\interfaces;

abstract class SQLite implements interfaces\TableModel
{
	/**
	 * @var \PDO
	 */
	protected $connection;

	/**
	 * @var \PDO
	 */
	protected static $defaultConnection;

	/**
	 * @param \PDO $connection
	 * @throws \UnderflowException
	 */
	public function __construct(\PDO $connection = null)
	{
		if ($connection)
			$this->setConnection($connection);
		elseif (self::$defaultConnection)
			$this->setConnection(self::$defaultConnection);
		else
			throw new \UnderflowException('You must pass $connection or setup self::$defaultConnection before calling this func.');
	}

	/**
	 * @param \PDO $pdo
	 * @return boolean
	 */
	public static function setDefaultConnection(\PDO $pdo)
	{
		self::$defaultConnection = $pdo;

		return true;
	}

	/**
	 * @return \PDO
	 */
	public static function getDefaultConnection()
	{
		return self::$defaultConnection;
	}

	/**
	 * @return \PDO
	 */
	public function getConnection()
	{
		return $this->connection;
	}

	/**
	 * @param \PDO $pdo
	 * @return $this
	 */
	public function setConnection(\PDO $pdo)
	{
		$this->connection = $pdo;

		return $this;
	}


	/**
	 * Find one row by condition.
	 *
	 * @param array $parts
	 * @param array $params
	 * @internal param string $condition
	 * @return array|null
	 */
	public function find(array $parts = [], array $params = [])
	{
		$result = $this->findAll(array_replace($parts, [
			'limit' => 1
		]), $params);

		return isset($result[0]) ? $result[0] : null;
	}

	/**
	 * Find all rows by condition and params.
	 *
	 * @param array $parts
	 * @param array $params
	 * @internal param string $condition
	 * @return array
	 */
	public function findAll(array $parts = [], array $params = [])
	{
		$stmt = $this->makeStmt($this->buildQuery($parts), $params);
		$stmt->execute();

		$out = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$stmt->closeCursor();

		return $out;
	}

	/**
	 * Find one row by primary key.
	 *
	 * @param array $pk
	 * @throws \OutOfBoundsException
	 * @return array
	 */
	public function findByPk(array $pk)
	{
		$params = [];
		$values = [];
		foreach ($this->getPk() as $key => $column) {
			if (!array_key_exists($key, $pk))
				throw new \OutOfBoundsException('Incorrect primary key value! No key for index "' . $key . '".');

			$values[] = 't.' . $column . '=:pk' . $key;
			$params[':pk' . $key] = $pk[$key];
		}

		return $this->find([
			'condition' => implode(' and ', $values)
		], $params);
	}

	/**
	 * @param \PDOStatement $stmt
	 * @param $params
	 * @return $this
	 */
	public function bindValues(\PDOStatement $stmt, $params)
	{
		foreach ($params as $key => $val)
			$stmt->bindValue($key, $val);

		return $this;
	}

	/**
	 * @param $sql
	 * @param array $params
	 * @return \PDOStatement
	 */
	public function makeStmt($sql, array $params = [])
	{
		$stmt = $this->connection->prepare($sql);
		$this->bindValues($stmt, $params);

		return $stmt;
	}

	/**
	 * Extremely simple query builder.
	 *
	 * @param array $parts
	 * @return string
	 */
	public function buildQuery(array $parts = [])
	{
		$parts = array_replace([
			'select' => '*',
		], $parts);

		$query = '
			select
				' . $parts['select'] . '
			from
				' . $this->getTableName() . ' t
		';

		if (!empty($parts['condition']))
			$query .= ' where ' . $parts['condition'];

		if (!empty($parts['group']))
			$query .= ' group by ' . $parts['group'];

		if (!empty($parts['order']))
			$query .= ' order by ' . $parts['order'];

		if (!empty($parts['limit']))
			$query .= ' limit ' . $parts['limit'];

		if (!empty($parts['offset']))
			$query .= ' offset ' . $parts['offset'];

		return $query;
	}
}
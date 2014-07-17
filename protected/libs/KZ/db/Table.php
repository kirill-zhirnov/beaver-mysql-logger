<?php

namespace KZ\db;
use KZ\db\interfaces;

abstract class Table implements interfaces\TableModel
{
	/**
	 * @var \PDO
	 */
	protected $connection;

	/**
	 * @param \PDO $connection
	 * @throws \UnderflowException
	 */
	public function __construct(\PDO $connection = null)
	{
		if ($connection)
			$this->setConnection($connection);
		elseif (static::getDefaultConnection())
			$this->setConnection(static::getDefaultConnection());
		else
			throw new \UnderflowException('You must pass $connection or setup static::$defaultConnection before calling this func.');
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
		$pkCondition = $this->buildPkCondition($pk);

		return $this->find([
			'condition' => $pkCondition['values']
		], $pkCondition['params']);
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
		$stmt = $this->makeStmt($this->buildSelectQuery($parts), $params);
		$stmt->execute();

		$out = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$stmt->closeCursor();

		return $out;
	}

	/**
	 * @param \PDO $pdo
	 * @return boolean
	 */
	public static function setDefaultConnection(\PDO $pdo)
	{
		static::$defaultConnection = $pdo;

		return true;
	}

	/**
	 * @return \PDO
	 */
	public static function getDefaultConnection()
	{
		return static::$defaultConnection;
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
	 * Insert row.
	 *
	 * @param array $values
	 * @return $this
	 */
	public function insert(array $values)
	{
		$params = [];
		$query = $this->buildInsertQuery([
			'set' => $values,
		], $params);

		$stmt = $this->makeStmt($query, $params);
		$stmt->execute();

		return $this;
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
	 * Update row by primary key.
	 *
	 * @param array $pk
	 * @param array $set
	 * @return $this
	 */
	public function updateByPk(array $pk, array $set)
	{
		$pkCondition = $this->buildPkCondition($pk, false);

		$query = $this->buildUpdateQuery([
			'set' => $set,
			'condition' => $pkCondition['values']
		], $pkCondition['params']);

		$stmt = $this->makeStmt($query, $pkCondition['params']);
		$stmt->execute();

		return $this;
	}

	/**
	 * Extremely simple query builder for SELECT.
	 *
	 * @param array $parts
	 * @return string
	 */
	public function buildSelectQuery(array $parts = [])
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

	/**
	 * Extremely simple query builder for INSERT.
	 *
	 * @param array $parts
	 * @param array $params
	 * @throws \OutOfBoundsException
	 * @return mixed
	 */
	public function buildInsertQuery(array $parts, array &$params = [])
	{
		if (empty($parts['set']) || !is_array($parts['set']))
			throw new \OutOfBoundsException('Key "set" must be in $parts and it must be an array.');

		$i= 0;
		$columns = [];
		$values = [];
		foreach ($parts['set'] as $column => $value) {
			$paramKey = ':val' . $i;
			$columns[] = $column;
			$values[] = $paramKey;
			$params[$paramKey] = $value;

			$i++;
		}

		$query = '
			insert into
				' . $this->getTableName() . ' (' . implode(', ', $columns) . ')
			values
				(' . implode(', ', $values) . ')
		';

		return $query;
	}

	/**
	 * Extremely simple query builder for UPDATE.
	 *
	 * @param array $parts
	 * @param array $params
	 * @throws \OutOfBoundsException
	 * @return string
	 */
	public function buildUpdateQuery(array $parts, array &$params = [])
	{
		if (empty($parts['set']) || !is_array($parts['set']))
			throw new \OutOfBoundsException('Key "set" must be in $parts and it must be an array.');

		$i= 0;
		$set = [];
		foreach ($parts['set'] as $column => $value) {
			$paramKey = ':set' . $i;
			$set[] =  $column . '=' . $paramKey;
			$params[$paramKey] = $value;

			$i++;
		}

		$query = '
			update
				' . $this->getTableName() . '
			set
				' . implode(', ', $set) . '
		';

		if (!empty($parts['condition']))
			$query .= ' where ' . $parts['condition'];

		if (!empty($parts['order']))
			$query .= ' order by ' . $parts['order'];

		if (!empty($parts['limit']))
			$query .= ' limit ' . $parts['limit'];

		return $query;
	}

	/**
	 * @param array $pk
	 * @param bool $addAlias
	 * @throws \OutOfBoundsException
	 * @return array
	 */
	protected function buildPkCondition(array $pk, $addAlias = true)
	{
		$params = [];
		$values = [];
		foreach ($this->getPk() as $key => $column) {
			if (!array_key_exists($key, $pk))
				throw new \OutOfBoundsException('Incorrect primary key value! No key for index "' . $key . '".');

			$alias = $addAlias ? 't.' : '';
			$values[] = $alias . $column . '=:pk' . $key;
			$params[':pk' . $key] = $pk[$key];
		}

		return [
			'values' => implode(' and ', $values),
			'params' => $params
		];
	}
}
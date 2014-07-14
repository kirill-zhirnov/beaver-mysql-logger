<?php

namespace KZ\grid;


abstract class Grid implements interfaces\Grid
{
	/**
	 * @var \KZ\app\Registry
	 */
	protected $registry;

	/**
	 * @var \KZ\db\interfaces\TableModel
	 */
	protected $table;

	/**
	 * @var Pager
	 */
	protected $pager;

	/**
	 * SQL query
	 *
	 * @var string
	 */
	protected $query;

	/**
	 * Params for query.
	 *
	 * @var array
	 */
	protected $params = [];

	/**
	 * @var array
	 */
	protected $rows;

	/**
	 * @param \KZ\app\Registry $registry
	 * @param \KZ\db\interfaces\TableModel $table
	 */
	public function __construct(\KZ\app\Registry $registry, \KZ\db\interfaces\TableModel $table)
	{
		$this->registry = $registry;
		$this->table = $table;
	}

	/**
	 * @return \KZ\app\Registry
	 */
	public function getRegistry()
	{
		return $this->registry;
	}

	/**
	 * @return \KZ\db\interfaces\TableModel
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 * @return $this
	 */
	public function reset()
	{
		$this->pager = null;
		$this->rows = null;
		$this->query = null;
		$this->params = [];

		return $this;
	}

	public function buildCountQuery($sql, $countExpression = 'count(*)')
	{
		$sql = preg_replace_callback('#^\s*select\s+(.+)\s+from#isU', function() use($countExpression) {
			return 'select ' . $countExpression . ' from';
		}, $sql);

		return preg_replace('#order by\s+.*$#is', '', $sql);
	}

	/**
	 * @return array
	 */
	public function getRows()
	{
		if (!isset($this->rows)) {
			$query = $this->getQuery();
			$pager = $this->getPager();

			$query .= ' LIMIT ' . $pager->getLimit() . ' OFFSET ' . $pager->getOffset();
			$stmt = $this->table->makeStmt($query, $this->getParams());
			$stmt->execute();

			$this->rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			$stmt->closeCursor();
		}

		return $this->rows;
	}

	/**
	 * @return string
	 */
	public function getQuery()
	{
		if (!$this->query)
			$this->query = $this->buildQuery();

		return $this->query;
	}

	/**
	 * @return array
	 */
	public function getParams()
	{
		if (!$this->params)
			$this->getQuery();

		return $this->params;
	}

	/**
	 * @return interfaces\Pager
	 */
	public function getPager()
	{
		if ($this->pager)
			return $this->pager;

		$stmt = $this->table->makeStmt(
			$this->buildCountQuery($this->getQuery()),
			$this->getParams()
		);
		$stmt->execute();

		$row = $stmt->fetch(\PDO::FETCH_NUM);
		$stmt->closeCursor();

		$this->pager = $this->registry->getKit()->makePager($row[0]);

		return $this->pager;
	}

	abstract protected function buildQuery();
}
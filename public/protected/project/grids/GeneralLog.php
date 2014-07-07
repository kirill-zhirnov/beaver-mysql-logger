<?php

namespace grids;
use KZ\grid;
use KZ\grid\interfaces\Pager;

class GeneralLog extends grid\Grid
{
	/**
	 * @var \KZ\model\Filter
	 */
	protected $filter;

	/**
	 * @var array
	 */
	protected $rows;

	/**
	 * @var Pager
	 */
	protected $pager;

	/**
	 * SQL query with params since we need it twice: for rows and for pager.
	 *
	 * @var array
	 */
	protected $query;

	/**
	 * @param \KZ\Filter $filter
	 * @return $this
	 */
	public function setFilter(\KZ\Filter $filter)
	{
		$this->filter = $filter;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getRows()
	{
		if (isset($this->rows))
			return $this->rows;

		return [];
	}

	/**
	 * @return Pager
	 */
	public function getPager()
	{
		if ($this->pager)
			return $this->pager;

		$sql = $this->buildQuery();
		//preg_replace select ... -> select count()
	}

	/**
	 * @return $this
	 */
	public function reset()
	{
		$this->pager = null;
		$this->rows = null;
		$this->query = null;

		return $this;
	}

	/**
	 * @throws \RuntimeException
	 * @return array
	 */
	protected function buildQuery()
	{
		if ($this->query)
			return $this->query;

		if (!$this->filter)
			throw new \RuntimeException('You must setup filter before calling this method!');

		$this->filter->makeFilters();

		$params = [];
		$where = [];
		$order = '';

		$this->appendFilterCondition($where, $order, $params);

		$sql = 'select t.* from general_log t';
		if ($where)
			$sql .= ' where ' . implode(' and ', $where);

		if ($order)
			$sql .= ' order by ' . $order;

		$this->query = [
			'sql' => $sql,
			'params' => $params
		];

		return $this->query;
	}

	protected function appendFilterCondition(&$where, &$order, &$params)
	{
		foreach ($this->filter->getAttrNames() as $attr) {
			$value = $this->filter->getAttribute($attr);

			if (!isset($value))
				continue;

			switch ($attr) {
				case 'threadId':
					$where[] = 't.thread_id = :threadId';
					$params[':threadId'] = $value;
					break;
				case 'serverId':
					$where[] = 't.server_id = :serverId';
					$params[':serverId'] = $value;
					break;
				case 'commandType':
					$where[] = 't.command_type = :commandType';
					$params[':commandType'] = $value;
					break;
				case 'argument':
					$where[] = 't.argument like :argument';
					$params[':argument'] = '%' . $value . '%';
					break;
				case 'sortBy':
					$mode = isset($this->filter->sortMode) ? $this->filter->sortMode : 'asc';
					$order = $value . ' ' . $mode;
					break;
				default:
					throw new \RuntimeException('Unknown attribute name "' . $attr . '".');
			}
		}
	}
} 
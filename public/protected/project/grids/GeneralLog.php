<?php

namespace grids;
use KZ\grid;
use KZ\grid\interfaces;

class GeneralLog extends grid\Grid
{
	/**
	 * @var \KZ\model\Filter
	 */
	protected $filter;

	/**
	 * @param \KZ\model\Filter $filter
	 * @return $this
	 */
	public function setFilter(\KZ\model\Filter $filter)
	{
		$this->filter = $filter;

		return $this;
	}

	public function getPager()
	{
		$pager = parent::getPager();
		$pager
			->setPageSize(100)
			->setCurrentPage($this->filter->p)
			->setPageRange(24)
		;

		return $pager;
	}


	/**
	 * @throws \RuntimeException
	 * @return array
	 */
	public function buildQuery()
	{
		if (!$this->filter)
			throw new \RuntimeException('You must setup filter before calling this method!');

		$this->filter->makeFilters();

		$this->params = [];
		$where = [];
		$order = '';

		$this->appendFilterCondition($where, $order);

		$sql = "
			select
				t.*
			from
				general_log t
				inner join (
					select
						thread_id,
						concat(UNIX_TIMESTAMP(max(event_time)), ' ', thread_id) as maxTime
					from
						general_log
					group by thread_id
				) t2 on t.thread_id = t2.thread_id
		";

		if ($where)
			$sql .= ' where ' . implode(' and ', $where);

		if ($order)
			$sql .= ' order by ' . $order;

		$this->query = $sql;

		return $this->query;
	}

	protected function appendFilterCondition(&$where, &$order)
	{
		foreach ($this->filter->getAttrNames() as $attr) {
			$value = $this->filter->getAttribute($attr);

			if (!isset($value))
				continue;

			switch ($attr) {
				case 'threadId':
					$where[] = 't.thread_id = :threadId';
					$this->params[':threadId'] = $value;
					break;
				case 'serverId':
					$where[] = 't.server_id = :serverId';
					$this->params[':serverId'] = $value;
					break;
				case 'commandType':
					$where[] = 't.command_type = :commandType';
					$this->params[':commandType'] = $value;
					break;
				case 'argument':
					$where[] = 't.argument like :argument';
					$this->params[':argument'] = '%' . $value . '%';
					break;
				case 'sortBy':
					$order = $this->getOrderSql($value);
					break;
			}
		}
	}

	protected function getOrderSql($sort)
	{
		switch ($sort) {
			case 'default';
				return 't2.maxTime desc, t.event_time desc';
		}
	}
} 
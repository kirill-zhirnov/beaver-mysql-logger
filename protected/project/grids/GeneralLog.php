<?php

namespace grids;
use KZ\grid;
use KZ\grid\interfaces;

class GeneralLog extends grid\Grid
{
	protected $threadsCount;

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

	/**
	 * @return \models\GeneralLogFilter
	 */
	public function getFilter()
	{
		return $this->filter;
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
				t.*,
				if (
					t.command_type = 'Connect',
					0,
					if (t.command_type = 'Quit', 2, 1)
				) as orderTypeKey,
				currentDb.command_type as db_command_type,
				currentDb.argument as db_argument
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
				left join (
					select
						s1.thread_id,
						s1.command_type,
						s1.argument
					from
						general_log s1
						inner join (
							select
								thread_id,
								max(event_time) as event_time
							from
								general_log
							where
								command_type in ('Connect', 'Init DB')
							group by
								thread_id
						) s2 on s1.thread_id = s2.thread_id and s1.event_time = s2.event_time
					where
						s1.command_type in ('Connect', 'Init DB')
					group by
						s1.thread_id
					order by s1.event_time desc
				) as currentDb on currentDb.thread_id = t.thread_id
		";

		if ($where)
			$sql .= ' where ' . implode(' and ', $where);

		if ($order)
			$sql .= ' order by ' . $order;

		$this->query = $sql;

		return $this->query;
	}

	public function reset(): self
	{
		$this->treadsCount = null;

		return parent::reset();
	}

	public function getTreadsCount()
	{
		if (isset($this->threadsCount))
			return $this->threadsCount;

		$stmt = $this->table->makeStmt(
			$this->buildCountQuery($this->getQuery(), 'count(distinct t.thread_id)'),
			$this->getParams()
		);
		$stmt->execute();

		$row = $stmt->fetch(\PDO::FETCH_NUM);
		$stmt->closeCursor();

		$this->threadsCount = intval($row[0]);

		return $this->threadsCount;
	}

	protected function appendFilterCondition(&$where, &$order)
	{
		foreach ($this->filter->getAttrNames() as $attr) {
			$value = $this->filter->getAttribute($attr);

			if (!isset($value) || $value == '')
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
				case 'eventTime':
					$where[] = 't.event_time like :eventTime';
					$this->params[':eventTime'] = $value . '%';
					break;
				case 'userHost':
					$where[] = 't.user_host like :userHost';
					$this->params[':userHost'] = '%' . $value . '%';
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
			case 'default':
				return 't2.maxTime desc, t.event_time desc, orderTypeKey desc';
			case 'event_time_asc':
				return 't.event_time asc';
			case 'event_time_desc':
				return 't.event_time desc';
			case 'thread_id_asc':
				return 't.thread_id asc';
			case 'thread_id_desc':
				return 't.thread_id desc';
		}
	}
} 
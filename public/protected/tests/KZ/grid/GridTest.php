<?php

namespace KZ\grid;

class GridTest extends \PHPUnit_Framework_TestCase
{
	public function testCountQuery()
	{
		$grid = $this->makeGrid();
		$this->assertEquals(
			'select count(*) from xx_test where',
			$grid->buildCountQuery('select t.*, t.aa from xx_test where')
		);
	}

	public function testCountQueryBig()
	{
		$query = "
			select
				t.*,
				if (
					command_type = 'Connect',
					0,
					if (command_type = 'Quit', 2, 1)
				) as orderTypeKey
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
		 order by
		 	t2.maxTime desc,
		 	t.event_time desc,
		 	orderTypeKey desc
		";

		$expected = "select count(*) from
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

		$grid = $this->makeGrid();
		$this->assertEquals(
			trim($expected),
			trim($grid->buildCountQuery($query))
		);
	}

	public function testCountQueryWithNewLines()
	{
		$query = "
			select
				t.*,
				if (t.a, 1, 0),
				if (t.b, 1, 0)
			from
				xx_test
			where
		";

		$expected = "select count(*) from
				xx_test
			where
		";

		$grid = $this->makeGrid();
		$this->assertEquals(
			trim($expected),
			trim($grid->buildCountQuery($query))
		);
	}

	public function testGetPager()
	{
		$registry = new \KZ\app\Registry();
		$registry->setKit(new \KZ\app\Kit([]));

		$stmt = $this->getMock('stdClass', ['fetch', 'closeCursor', 'execute']);
		$stmt
			->expects($this->once())
			->method('fetch')
			->will($this->returnValue([75]))
		;

		$table = $this->makeTable(['getTableName', 'getPk', 'makeStmt']);
		$table
			->expects($this->once())
			->method('makeStmt')
			->will($this->returnValue($stmt))
		;

		$grid = $this->makeGrid(['getRows', 'buildQuery'], [
			$registry, $table
		]);

		$pager = $grid->getPager();
		$this->assertInstanceOf('\KZ\grid\interfaces\Pager', $pager);
		$this->assertEquals(75, $pager->getItemCount());
	}

	/**
	 * @param array $methods
	 * @param array $construct
	 * @return \KZ\grid\Grid
	 */
	protected function makeGrid(array $methods = ['getRows', 'buildQuery'], array $construct = null)
	{
		if (is_null($construct))
			$construct = [
				$this->makeRegistry(),
				$this->makeTable()
			];

		return $this->getMock('\KZ\grid\Grid', $methods, $construct);
	}

	/**
	 * @return \KZ\Registry
	 */
	protected function makeRegistry()
	{
		return $this->getMock('\KZ\app\Registry', null);
	}

	/**
	 * @param array $methods
	 * @return \KZ\db\Table
	 */
	protected function makeTable(array $methods = ['getTableName', 'getPk'])
	{
		$pdo = new \KZ\db\PDOMock();
		return $this->getMock('\KZ\db\table\Mysql', $methods, [$pdo]);
	}
}
 
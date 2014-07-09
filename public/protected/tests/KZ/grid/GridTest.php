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

	public function testGetPager()
	{
		$registry = new \KZ\app\Registry();
		$registry->setKit(new \KZ\app\Kit([]));

		$stmt = $this->getMock('stdClass', ['fetch', 'closeCursor']);
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
 
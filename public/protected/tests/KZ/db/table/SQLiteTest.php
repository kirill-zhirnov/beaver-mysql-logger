<?php

namespace KZ\db\table;


class SQLiteTest extends \PHPUnit_Framework_TestCase
{
	public function testBasic()
	{
		$pdo = $this->getMock('\KZ\db\PDOMock', ['prepare']);
		$pdo->expects($this->once())
			->method('prepare')
			->will($this->returnValue($this->getMock('\PDOStatement')))
		;

		$sqlLite = $this->makeSQLiteMock($pdo);
		$this->assertInstanceOf('\KZ\db\PDOMock', $sqlLite->getConnection());
		$this->assertInstanceOf('\PDOStatement', $sqlLite->makeStmt('select * from test'));
	}

	public function testBindValues()
	{
		$stmt = $this->getMock('\PDOStatement', ['bindValue']);
		$stmt->expects($this->at(0))
			->method('bindValue')
			->with($this->equalTo('a'), $this->equalTo('1'))
		;

		$stmt->expects($this->at(1))
			->method('bindValue')
			->with($this->equalTo('b'), $this->equalTo('2'))
		;

		$stmt
			->expects($this->exactly(2))
			->method('bindValue')
		;

		$sqlLite = $this->makeSQLiteMock();
		$sqlLite->bindValues($stmt, [
			'a' => 1,
			'b' => 2
		]);
	}

	public function testBuildQuery()
	{
		$sqlLite = $this->makeSQLiteMock();
		$sqlLite->expects($this->once())
			->method('getTableName')
			->will($this->returnValue('xx_test'))
		;

		$query = trim($sqlLite->buildQuery([
			'select' => 'a,b',
			'condition' => 'a=:param',
			'group' => 'a',
			'order' => 'b',
			'limit' => 1,
			'offset' => 1
		]));

		$query = preg_replace('#\n#i', '', $query);
		$query = preg_replace('#\s+#i', ' ', $query);

		$this->assertEquals(
			'select a,b from xx_test t where a=:param group by a order by b limit 1 offset 1',
			$query
		);
	}

	public function testFindByPkException()
	{
		$this->setExpectedException('OutOfBoundsException', 'Incorrect primary key value! No key for index "1".');

		$sqlLite = $this->makeSQLiteMock();
		$sqlLite
			->expects($this->once())
			->method('getPk')
			->will($this->returnValue(['a', 'b']))
		;

		$sqlLite->findByPk([1]);
	}

	public function testFindByPk()
	{
		$stmt = $this->getMock('\PDOStatement', ['execute', 'fetchAll', 'closeCursor']);

		$sqlLite = $this->makeSQLiteMock(null, ['makeStmt', 'getPk', 'getTableName']);

		$sqlLite
			->expects($this->once())
			->method('makeStmt')
			->with(
				$this->callback(function($sql) {
					$sql = trim($sql);
					$sql = preg_replace('#\n#i', '', $sql);
					$sql = preg_replace('#\s+#i', ' ', $sql);

					$expected = 'select * from xx_test t where t.a=:pk0 and t.b=:pk1 limit 1';
					return $sql == $expected;
				}),
				$this->equalTo([
					':pk0' => 1,
					':pk1' => 2
				])
			)
			->will($this->returnValue($stmt))
		;

		$sqlLite->expects($this->once())
			->method('getPk')
			->will($this->returnValue(['a', 'b']))
		;

		$sqlLite->expects($this->once())
			->method('getTableName')
			->will($this->returnValue('xx_test'))
		;

		$sqlLite->findByPk([1, 2]);
	}

	/**
	 * @param \PDO $pdo
	 * @param array $methods
	 * @return SQLite
	 */
	protected function makeSQLiteMock(\PDO $pdo = null, $methods = ['getTableName', 'getPk'])
	{
		if (!$pdo)
			$pdo = $this->getMock('\KZ\db\PDOMock');

		$sqlite = $this->getMock('\KZ\db\table\SQLite', $methods, [$pdo]);

		return $sqlite;
	}
}
 
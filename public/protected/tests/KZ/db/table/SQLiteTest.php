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

	public function testBuildSelectQuery()
	{
		$sqlLite = $this->makeSQLiteMock();
		$sqlLite->expects($this->once())
			->method('getTableName')
			->will($this->returnValue('xx_test'))
		;

		$query = trim($sqlLite->buildSelectQuery([
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

	public function testBuildUpdateQuery()
	{
		$sqlLite = $this->makeSQLiteMock();
		$sqlLite->expects($this->once())
			->method('getTableName')
			->will($this->returnValue('xx_test'))
		;

		$params = [':pk' => 1];
		$query = trim($sqlLite->buildUpdateQuery([
			'set' => [
				'a' => 'b',
				'c' => 'd'
			],
			'condition' => 'pk=:pk',
			'order' => 'b',
			'limit' => 1,
			'offset' => 5
		], $params));

		$query = preg_replace('#\n#i', '', $query);
		$query = preg_replace('#\s+#i', ' ', $query);

		$this->assertEquals(
			'update xx_test set a=:set0, c=:set1 where pk=:pk order by b limit 1 offset 5',
			$query
		);
		$this->assertEquals([
			':pk' => 1,
			':set0' => 'b',
			':set1' => 'd'
		], $params);
	}

	public function testBuildUpdateQueryNoSet()
	{
		$sqlLite = $this->makeSQLiteMock();
		$this->setExpectedException('OutOfBoundsException', 'Key "set" must be in $parts and it must be an array.');
		$sqlLite->buildUpdateQuery([]);
	}

	public function testUpdateByPk()
	{
		$stmt = $this->getMock('\PDOStatement', ['execute', 'closeCursor']);
		$sqlLite = $this->makeSQLiteMock(null, ['makeStmt', 'getPk', 'getTableName']);

		$sqlLite
			->expects($this->once())
			->method('makeStmt')
			->with(
				$this->callback(function($sql) {
					$sql = trim($sql);
					$sql = preg_replace('#\n#i', '', $sql);
					$sql = preg_replace('#\s+#i', ' ', $sql);

					$expected = 'update xx_test set c=:set0 where a=:pk0 and b=:pk1';

					return $sql == $expected;
				}),
				$this->equalTo([
					':set0' => 'd',
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

		$sqlLite->updateByPk([1, 2], ['c' => 'd']);
	}

	public function testBuildInsertQuery()
	{
		$sqlLite = $this->makeSQLiteMock();
		$sqlLite->expects($this->once())
			->method('getTableName')
			->will($this->returnValue('xx_test'))
		;

		$params = [];
		$query = trim($sqlLite->buildInsertQuery([
			'set' => [
				'a' => 'b',
				'c' => 'd'
			]
		], $params));

		$query = preg_replace('#\n#i', '', $query);
		$query = preg_replace('#\s+#i', ' ', $query);

		$this->assertEquals(
			'insert into xx_test (a, c) values (:val0, :val1)',
			$query
		);
		$this->assertEquals([
			':val0' => 'b',
			':val1' => 'd'
		], $params);
	}

	public function testBuildInsertQueryNoSet()
	{
		$sqlLite = $this->makeSQLiteMock();

		$this->setExpectedException('OutOfBoundsException', 'Key "set" must be in $parts and it must be an array.');
		$sqlLite->buildInsertQuery([]);
	}

	public function testInsert()
	{
		$stmt = $this->getMock('\PDOStatement', ['execute', 'closeCursor']);
		$sqlLite = $this->makeSQLiteMock(null, ['makeStmt', 'getPk', 'getTableName']);

		$sqlLite
			->expects($this->once())
			->method('makeStmt')
			->with(
				$this->callback(function($sql) {
					$sql = trim($sql);
					$sql = preg_replace('#\n#i', '', $sql);
					$sql = preg_replace('#\s+#i', ' ', $sql);

					$expected = 'insert into xx_test (a, c) values (:val0, :val1)';

					return $sql == $expected;
				}),
				$this->equalTo([
					':val0' => 'b',
					':val1' => 'd'
				])
			)
			->will($this->returnValue($stmt))
		;

		$sqlLite->expects($this->once())
			->method('getTableName')
			->will($this->returnValue('xx_test'))
		;

		$sqlLite->insert([
			'a' => 'b',
			'c' => 'd'
		]);
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
 
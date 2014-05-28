<?php

use KZ\db;

class ConnectionStorageTest extends PHPUnit_Framework_TestCase
{
	public function testInterface()
	{
		$pdo = $this->getMock('\KZ\db\PDOMock');
		$connectionStorage = new db\ConnectionStorage();

		$this->assertEquals('test', $connectionStorage->add($pdo, db\ConnectionStorage::MYSQL, 'test'));
		$this->assertInstanceOf('PDO', $connectionStorage->getByName('test'));
		$this->assertEquals(['test' => $pdo], $connectionStorage->getByType(db\ConnectionStorage::MYSQL));
		$this->assertNull($connectionStorage->getDefault());

		$connectionStorage->add($pdo, db\ConnectionStorage::MYSQL, null, true);
		$this->assertInstanceOf('PDO', $connectionStorage->getDefault());
	}
}
 
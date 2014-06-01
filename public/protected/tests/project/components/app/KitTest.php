<?php

namespace project\components\app;
use KZ\db;

class KitTest extends \PHPUnit_Framework_TestCase
{
	public function testMakeMysqlConnections()
	{
		$config = ['db' => []];

		$kit = $this->getMock('\components\app\Kit', ['makeMysqlConnections', 'makePdo'], [$config]);

		$kit->expects($this->once())
			->method('makeMysqlConnections')
		;

		$kit->expects($this->once())
			->method('makePdo')
			->will($this->returnValue(new db\PDOMock))
		;


		$this->assertInstanceOf('\KZ\db\interfaces\ConnectionStorage', $kit->makeConnectionStorage());
	}
}
 
<?php

namespace KZ\app;

use KZ\db;

class KitTest extends \PHPUnit_Framework_TestCase
{
	public function testMakeConnectionStorage()
	{
		$config = [
			'db' => [
				'dsn' => 'testDsn'
			]
		];

		$pdoMock = new db\PDOMock('test');

		$kit = $this->getMock('\KZ\app\Kit', ['makePdo'], [$config]);
		$kit->expects($this->once())
			->method('makePdo')
			->with($this->identicalTo($config['db']))
			->will($this->returnValue($pdoMock))
		;

		$cs = $kit->makeConnectionStorage();
		$this->assertInstanceOf('\KZ\db\interfaces\ConnectionStorage', $cs);
		$this->assertEquals($pdoMock, $cs->getDefault());
	}

	public function testMakeConnectionStorageOwnClass()
	{
		$csMock = $this->getMock('\KZ\db\ConnectionStorage');

		$config = [
			'db' => [],
			'components' => [
				'connectionStorage' => [
					'class'=> get_class($csMock)
				]
			]
		];

		$kit = $this->getMock('\KZ\app\Kit', ['makePdo'], [$config]);
		$kit->expects($this->once())
			->method('makePdo')
			->will($this->returnValue(new db\PDOMock('test')))
		;

		$cs = $kit->makeConnectionStorage();
		$this->assertInstanceOf('\KZ\db\interfaces\ConnectionStorage', $cs);
		$this->assertEquals($csMock, $cs);
	}

	public function testMakeRegistry()
	{
		$kit = new \KZ\app\Kit([]);
		$this->assertInstanceOf('\KZ\app\interfaces\Registry', $kit->makeRegistry());

		$registryMock = $this->getMock('\KZ\app\Registry');
		$kit = new \KZ\app\Kit([
			'components' => [
				'registry' => [
					'class' => get_class($registryMock)
				]
			]
		]);
		$this->assertEquals($registryMock, $kit->makeRegistry());
	}
}
 
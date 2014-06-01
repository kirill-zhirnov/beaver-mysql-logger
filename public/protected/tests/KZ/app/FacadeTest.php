<?php

namespace KZ\app;

class FacadeTest extends \PHPUnit_Framework_TestCase
{
	public function testInitialize()
	{
		//stubbing kit to prevent PDO creation
		$kit = $this->getMock('\KZ\app\Kit', ['makeConnectionStorage'], [[]]);
		$kit->expects($this->once())
			->method('makeConnectionStorage')
			->will($this->returnValue(new \KZ\db\ConnectionStorage))
		;

		$config = ['testConfig'];
		/** @var \KZ\app\Facade $facade */
		$facade = $this->getMock('\KZ\app\Facade', ['makeFrontController', 'makeKit'], [$config]);
		$facade->expects($this->once())
			->method('makeKit')
			->will($this->returnValue($kit))
		;

		$this->assertFalse($facade->isInitialized());
		$facade->initialize();
		$this->assertTrue($facade->isInitialized());

		/** @var \KZ\app\interfaces\Registry $registry */
		$registry = $facade->getRegistry();
		$this->assertInstanceOf('\KZ\app\interfaces\Registry', $registry);

		//verify, that all necessary components were set up.
		$this->assertEquals($config, $registry->getConfig());
		$this->assertInstanceOf('\KZ\app\interfaces\Kit', $registry->getKit());
		$this->assertInstanceOf('\KZ\db\interfaces\ConnectionStorage', $registry->getConnectionStorage());
	}

	public function testMakeKit()
	{
		$facade = $this->getMock('\KZ\app\Facade', ['makeFrontController'], [[]]);
		$this->assertInstanceOf('\KZ\app\interfaces\Kit', $facade->makeKit());

		//try to specify custom class name:
		$kit = $this->getMock('\KZ\app\Kit', [], [[]]);

		$config = [
			'components' => ['kit' => ['class' => get_class($kit)]]
		];
		$facade = $this->getMock('\KZ\app\Facade', ['makeFrontController'], [$config]);
		$this->assertInstanceOf(get_class($kit), $facade->makeKit());
	}
}
 
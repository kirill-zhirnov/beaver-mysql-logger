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
		$facade = $this->getMock('\KZ\app\Facade', ['makeRequest', 'makeControllerKit', 'makeKit'], [$config]);
		$facade
			->expects($this->once())
			->method('makeKit')
			->will($this->returnValue($kit))
		;

		$facade
			->expects($this->once())
			->method('makeRequest')
			->will($this->returnValue($this->makeRequest()))
		;

		$facade
			->expects($this->once())
			->method('makeControllerKit')
			->will($this->returnValue($this->makeControllerKit()))
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
		$this->assertInstanceOf('\KZ\controller\Request', $registry->getRequest());
		$this->assertInstanceOf('\KZ\controller\Front', $facade->getFrontController());
	}

	public function testMakeKit()
	{
		$facade = $this->getMock('\KZ\app\Facade', ['makeRequest', 'makeControllerKit'], [[]]);
		$this->assertInstanceOf('\KZ\app\interfaces\Kit', $facade->makeKit());

		//try to specify custom class name:
		$kit = $this->getMock('\KZ\app\Kit', [], [[]]);

		$config = [
			'components' => ['kit' => ['class' => get_class($kit)]]
		];
		$facade = $this->getMock('\KZ\app\Facade', ['makeRequest', 'makeControllerKit'], [$config]);
		$this->assertInstanceOf(get_class($kit), $facade->makeKit());
	}

	/**
	 * @param string $route
	 * @return \KZ\controller\Request
	 */
	protected function makeRequest($route = '')
	{
		return $this->getMock('\KZ\controller\Request', ['getScriptName'], [$route]);
	}

	/**
	 * @return \KZ\Controller\Kit
	 */
	protected function makeControllerKit()
	{
		return $this->getMockBuilder('\KZ\Controller\Kit')
			->disableOriginalConstructor()
			->getMock()
		;
	}
}
 
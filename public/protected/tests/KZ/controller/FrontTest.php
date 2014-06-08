<?php

namespace KZ\controller;

class FrontTest extends \PHPUnit_Framework_TestCase
{
	public function testMakeControllerChain()
	{
		$registry = $this->makeAppRegistry()
			->setRequest($this->makeRequest())
			->setKit($this->makeKit())
		;

		$controller = $this->getControllerInstance();

		$chain = [['instance' => $controller, 'action' => 'test']];

		/** @var Front $front */
		$front = $this->getMock('\KZ\controller\Front', ['makeController'], [$this->makeControllerKitMock(), $registry]);
		$front->expects($this->once())
			->method('makeController')
			->will($this->returnValue($chain[0]))
		;
		$front->init();

		$this->assertInstanceOf('\KZ\controller\Chain', $front->getControllerChain());
		foreach ($front->getControllerChain() as $key => $item) {
			$this->assertEquals($chain[$key]['instance'], $item['instance']);
			$this->assertEquals($chain[$key]['action'], $item['action']);
		}
	}

	/**
	 * @return \KZ\Controller\Kit
	 */
	protected function makeControllerKitMock()
	{
		return $this->getMockBuilder('\KZ\Controller\Kit')
			->disableOriginalConstructor()
			->getMock()
		;
	}

	/**
	 * @return \KZ\app\Registry
	 */
	protected function makeAppRegistry()
	{
		return new \KZ\app\Registry();
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
	 * @return \KZ\app\Kit
	 */
	protected function makeKit()
	{
		return new \KZ\app\Kit([]);
	}

	/**
	 * @return \KZ\Controller
	 */
	protected function getControllerInstance()
	{
		return $this->getMockBuilder('\KZ\Controller')
			->disableOriginalConstructor()
			->getMock()
		;
	}
}
 
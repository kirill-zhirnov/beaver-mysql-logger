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

	public function testMakeController()
	{
		$controller = $this->getControllerInstance();

		$controllerKit = $this->makeControllerKitMock();
		$controllerKit
			->expects($this->once())
			->method('makeController')
			->with($this->equalTo('sub/path'), $this->equalTo('controller1'), $this->equalTo('testAction'))
			->will($this->returnValue($controller))
		;

		$controllerKit
			->expects($this->any())
			->method('getActionMethod')
			->will($this->returnValue('testAction'))
		;

		/** @var Front $front */
		$front = $this->getMock('\KZ\controller\Front', null, [$controllerKit, $this->makeAppRegistry()]);

		$this->assertEquals([
			'instance' => $controller,
			'action' => 'testAction'
		], $front->makeController('sub/path', 'controller1', 'testAction'));
	}

	public function testForwardException()
	{
		$this->setExpectedException('RuntimeException', 'You must call init to setup controllerChain before calling this method!');

		$front = $this->getMockBuilder('\KZ\Controller\Front')
			->disableOriginalConstructor()
			->setMethods(null)
			->getMock()
		;

		$front->forward('sub', 'controller', 'action1');
	}

	public function testForward()
	{
		$registry = $this->makeAppRegistry()
			->setRequest($this->makeRequest())
			->setKit($this->makeKit())
		;

		$controller = $this->getControllerInstance();
		$nextController = $this->getControllerInstance();

		$chain = [
			['instance' => $controller, 'action' => 'test'],
			['instance' => $nextController, 'action' => 'next'],
		];

		/** @var Front $front */
		$front = $this->getMock('\KZ\controller\Front', ['makeController'], [$this->makeControllerKitMock(), $registry]);
		$front
			->expects($this->at(0))
			->method('makeController')
			->with($this->equalTo(''), $this->equalTo('index'), $this->equalTo('index'))
			->will($this->returnValue($chain[0]))
		;

		$front
			->expects($this->at(1))
			->method('makeController')
			->with($this->equalTo('sub/path'), $this->equalTo('forwardedController'), $this->equalTo('action'))
			->will($this->returnValue($chain[1]))
		;

		$front
			->expects($this->exactly(2))
			->method('makeController')
		;

		$front->init();
		$front->forward('sub/path', 'forwardedController', 'action');

		foreach ($front->getControllerChain() as $key => $item) {
			$this->assertEquals($chain[$key]['instance'], $item['instance']);
			$this->assertEquals($chain[$key]['action'], $item['action']);
		}
	}

	public function testDoubleInitCall()
	{
		$registry = $this->makeAppRegistry()
			->setRequest($this->makeRequest())
			->setKit($this->makeKit())
		;

		$controller = $this->getControllerInstance();

		$chain = [
			['instance' => $controller, 'action' => 'test'],
		];

		/** @var Front $front */
		$front = $this->getMock('\KZ\controller\Front', ['makeController'], [$this->makeControllerKitMock(), $registry]);
		$front
			->expects($this->once())
			->method('makeController')
			->will($this->returnValue($chain[0]))
		;

		$front->init();
		$front->init();
	}

	public function testRun()
	{
		$registry = $this->makeAppRegistry()
			->setRequest($this->makeRequest())
			->setKit($this->makeKit())
			->setObserver($this->makeObserver())
		;

		$controller = $this->getControllerInstance();
		$controller
			->expects($this->once())
			->method('test')
		;

		$chain = [
			['instance' => $controller, 'action' => 'test'],
		];

		/** @var Front $front */
		$front = $this->getMock('\KZ\controller\Front', ['makeController'], [$this->makeControllerKitMock(), $registry]);
		$front
			->expects($this->once())
			->method('makeController')
			->will($this->returnValue($chain[0]))
		;

		$front->run();
	}

	public function testBeforeRunControllerChainEvent()
	{
		$registry = $this->makeAppRegistry()
			->setRequest($this->makeRequest())
			->setKit($this->makeKit())
			->setObserver($this->makeObserver())
		;

		$controller = $this->getControllerInstance();
		$controller
			->expects($this->once())
			->method('test')
		;

		$chain = [
			['instance' => $controller, 'action' => 'test'],
		];

		/** @var Front $front */
		$front = $this->getMock('KZ\controller\Front', ['makeController'], [$this->makeControllerKitMock(), $registry]);
		$front
			->expects($this->once())
			->method('makeController')
			->will($this->returnValue($chain[0]))
		;

		$listenerCalled = false;
		$registry->getObserver()->bind('KZ\controller\Front', 'beforeRunControllerChain', function() use(&$listenerCalled) {
			$listenerCalled = true;
		});

		$front->run();
		$this->assertTrue($listenerCalled);
	}

	public function testPreventRunControllerChain()
	{
		$registry = $this->makeAppRegistry()
			->setRequest($this->makeRequest())
			->setKit($this->makeKit())
			->setObserver($this->makeObserver())
		;

		$controller = $this->getControllerInstance();
		$controller
			->expects($this->never())
			->method('test')
		;

		$chain = [
			['instance' => $controller, 'action' => 'test'],
		];

		/** @var Front $front */
		$front = $this->getMock('KZ\controller\Front', ['makeController'], [$this->makeControllerKitMock(), $registry]);
		$front
			->expects($this->once())
			->method('makeController')
			->will($this->returnValue($chain[0]))
		;

		$listenerCalled = false;
		$registry->getObserver()->bind('KZ\controller\Front', 'beforeRunControllerChain', function($event) use(&$listenerCalled) {
			$listenerCalled = true;
			$event->preventDefault();
		});

		$front->run();
		$this->assertTrue($listenerCalled);
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testRedirect()
	{
		$front = $this->getMock('\KZ\controller\Front', ['makeController'], [
			$this->makeControllerKitMock(),
			$this->makeAppRegistry()
		]);

		$front->redirect('test.php', false);
		$this->assertContains('Location: test.php', xdebug_get_headers());
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
			->setMethods(['test'])
			->getMock()
		;
	}

	/**
	 * @return \KZ\event\Observer
	 */
	protected function makeObserver()
	{
		return new \KZ\event\Observer();
	}

	/**
	 * @return \KZ\Controller\Chain
	 */
	protected function makeControllerChain()
	{
		return $this->getMockBuilder('\KZ\Controller\Chain')
			->setMethods(null)
			->getMock()
		;
	}
}
 
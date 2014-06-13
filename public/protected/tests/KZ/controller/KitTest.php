<?php

namespace KZ\controller;
use KZ\controller;

class KitTest extends \PHPUnit_Framework_TestCase
{
	public function testSetConfig()
	{
		$kit = new controller\Kit('path', [
			'path' => 'new',
			'namespacePrefix' => 'testPrefix',
			'suffix' => 'testSuffix',
			'actionPrefix' => 'preffix'
		]);

		$this->assertEquals('path', $kit->getPath());
		$this->assertEquals('testPrefix', $kit->getNamespacePrefix());
		$this->assertEquals('preffix', $kit->getActionPrefix());
	}

	public function testCreateClassName()
	{
		$kit = new controller\Kit('path', ['namespacePrefix' => 'testPrefix']);

		$this->assertEquals('testPrefix\path\sub\Controller', $kit->createClassName('/path/SUB', 'ControLLer'));

		$kit = new controller\Kit('path', ['namespacePrefix' => '']);
		$this->setExpectedException('UnexpectedValueException', 'Namespace prefix must be non empty by security reasons.');
		$kit->createClassName('/path/SUB', 'ControLLer');
	}

	public function testMakeController()
	{
		$frontController = $this->getMockBuilder('\KZ\controller\Front')
			->disableOriginalConstructor()
			->getMock()
		;

		$kit = $this->getMock('\KZ\controller\Kit', ['createClassName'], ['']);
		$kit->setFrontController($frontController);

		$controller = $this->getMock('\KZ\Controller', ['actionTest'], [$frontController]);

		$kit->expects($this->once())
			->method('createClassName')
			->with($this->equalTo('sub/path'), $this->equalTo('index'))
			->will($this->returnValue(get_class($controller)))
		;

		$this->assertInstanceOf('\KZ\Controller', $kit->makeController('sub/path', 'index', 'test'));
	}

	public function testMakeControllerNoAction()
	{
		$frontController = $this->getMockBuilder('\KZ\controller\Front')
			->disableOriginalConstructor()
			->getMock()
		;

		$kit = $this->getMock('\KZ\controller\Kit', ['createClassName'], ['']);
		$kit->setFrontController($frontController);
		$controller = $this->getMock('\KZ\Controller', null, [$frontController]);

		$kit->expects($this->once())
			->method('createClassName')
			->with($this->equalTo('sub/path'), $this->equalTo('index'))
			->will($this->returnValue(get_class($controller)))
		;

		$this->assertFalse($kit->makeController('sub/path', 'index', 'test'));
	}

	public function testMakeControllerException()
	{
		$frontController = $this->getMockBuilder('\KZ\controller\Front')
			->disableOriginalConstructor()
			->getMock()
		;

		$kit = $this->getMock('\KZ\controller\Kit', ['createClassName'], ['']);
		$kit->setFrontController($frontController);

		$controller = $this->getMock('stdClass', ['actionTest']);

		$kit->expects($this->once())
			->method('createClassName')
			->with($this->equalTo('sub/path'), $this->equalTo('index'))
			->will($this->returnValue(get_class($controller)))
		;

		$this->setExpectedException('RuntimeException', 'Controller must be instance of \KZ\Controller.');
		$kit->makeController('sub/path', 'index', 'test');
	}

	public function testMakeRequest()
	{

	}
} 
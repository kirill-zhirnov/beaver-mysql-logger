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

	public function testMakeControllerChain()
	{
		$kit = new \KZ\app\Kit([]);
		$this->assertInstanceOf('\KZ\controller\Chain', $kit->makeControllerChain());

		$instanceMock = $this->getMock('\KZ\controller\Chain');
		$kit = new \KZ\app\Kit([
			'components' => [
				'controllerChain' => [
					'class' => get_class($instanceMock)
				]
			]
		]);
		$this->assertEquals($instanceMock, $kit->makeControllerChain());
	}

	public function testMakeFrontController()
	{
		$kit = new \KZ\app\Kit([]);

		$controllerKit = $this->getMockBuilder('\KZ\Controller\Kit')
			->disableOriginalConstructor()
			->getMock()
		;

		$registry = new \KZ\app\Registry([]);
		$this->assertInstanceOf('\KZ\controller\Front', $kit->makeFrontController($controllerKit, $registry));

		$instanceMock = $this->getMockBuilder('\KZ\controller\Front')
			->disableOriginalConstructor()
			->getMock()
		;

		$kit = new \KZ\app\Kit([
			'components' => [
				'frontController' => [
					'class' => get_class($instanceMock)
				]
			]
		]);

		$this->assertInstanceOf(get_class($instanceMock), $kit->makeFrontController($controllerKit, $registry));
	}

	public function testMakeHttpRequest()
	{
		$kit = new \KZ\app\Kit([]);
		$this->assertInstanceOf('\KZ\controller\request\Http', $kit->makeHttpRequest());

		$requestMock = $this->getMock('\KZ\controller\request\Http');
		$kit = new \KZ\app\Kit([
			'components' => [
				'httpRequest' => [
					'class' => get_class($requestMock)
				]
			]
		]);
		$this->assertEquals($requestMock, $kit->makeHttpRequest());
	}

	public function testMakeControllerKit()
	{
		$kitConfig = [
			'path' => 'test'
		];

		$kit = new \KZ\app\Kit([]);
		$this->assertInstanceOf('\KZ\controller\Kit', $kit->makeControllerKit($kitConfig));

		$kitMock = $this->getMock('\KZ\controller\Kit', null, [$kitConfig]);
		$kit = new \KZ\app\Kit([
			'components' => [
				'controllerKit' => [
					'class' => get_class($kitMock)
				]
			]
		]);
		$this->assertInstanceOf(get_class($kitMock), $kit->makeControllerKit($kitConfig));

		//no path in config - exception
		$this->setExpectedException('OutOfBoundsException', 'Key "path" must be in config.');
		$kit->makeControllerKit([]);
	}

	public function testMakeView()
	{
		$kitConfig = [
			'components' => [
				'view' => [
					'templatesPath' => '/this/is/path',
					'config' => [
						'extension' => '.ccc',
						'varNameForContent' => 'bbb'
					]
				]
			]
		];

		$kit = new \KZ\app\Kit($kitConfig);
		$view = $kit->makeView();

		$this->assertEquals('/this/is/path', $view->getTemplatesPath());
		$this->assertEquals('.ccc', $view->getExtension());
		$this->assertEquals('bbb', $view->getVarNameForContent());

		$view = $kit->makeView('/new/path', [
			'extension' => '.eee',
			'varNameForContent' => 'hhh'
		]);

		$this->assertEquals('/new/path', $view->getTemplatesPath());
		$this->assertEquals('.eee', $view->getExtension());
		$this->assertEquals('hhh', $view->getVarNameForContent());
	}

	public function testObserver()
	{
		$kit = new \KZ\app\Kit([]);
		$this->assertInstanceOf('\KZ\event\Observer', $kit->makeObserver());

		$requestMock = $this->getMock('\KZ\event\Observer');
		$kit = new \KZ\app\Kit([
			'components' => [
				'observer' => [
					'class' => get_class($requestMock)
				]
			]
		]);
		$this->assertEquals($requestMock, $kit->makeObserver());
	}
}
 
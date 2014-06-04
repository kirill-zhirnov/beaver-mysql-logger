<?php

namespace KZ\controller;

class RequestTest extends \PHPUnit_Framework_TestCase
{
	public function testDispatchRoute()
	{
		$request = $this->getMock('\KZ\controller\Request', null, ['controller/action']);
		$this->assertEquals('', $request->getControllerPath());
		$this->assertEquals('controller', $request->getController());
		$this->assertEquals('action', $request->getAction());

		$request = $this->getMock('\KZ\controller\Request', null, ['path/subpath/controller/action']);
		$this->assertEquals('path/subpath', $request->getControllerPath());
		$this->assertEquals('controller', $request->getController());
		$this->assertEquals('action', $request->getAction());

		//test on wrong routes:
	}

//	public function testEmptyRoute()
//	{}
}
 
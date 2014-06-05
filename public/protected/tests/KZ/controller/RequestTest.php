<?php

namespace KZ\controller;

class RequestTest extends \PHPUnit_Framework_TestCase
{
	public function testDispatchRoute()
	{
		$request = $this->getMock('\KZ\controller\Request', ['getScriptName'], ['controller/action']);
		$this->assertEquals('', $request->getControllerPath());
		$this->assertEquals('controller', $request->getController());
		$this->assertEquals('action', $request->getAction());

		$request = $this->getMock('\KZ\controller\Request', ['getScriptName'], ['path1/sub_path/controller/action']);
		$this->assertEquals('path1/sub_path', $request->getControllerPath());
		$this->assertEquals('controller', $request->getController());
		$this->assertEquals('action', $request->getAction());

		$request = $this->getMock('\KZ\controller\Request', ['getScriptName'], ['controller-name/action']);
		$this->setExpectedException('UnexpectedValueException', 'Route must have at least 2 parts separated by "/".');
		$request->getControllerPath();

		$request = $this->getMock('\KZ\controller\Request', ['getScriptName'], ['test']);
		$this->setExpectedException('UnexpectedValueException', 'Route must have at least 2 parts separated by "/".');
		$request->getControllerPath();

		$request = $this->getMock('\KZ\controller\Request', ['getScriptName'], ['prefix/pre.fix/controller/action']);
		$this->setExpectedException('UnexpectedValueException', 'Route must have at least 2 parts separated by "/".');
		$request->getControllerPath();
	}

	public function testEmptyRoute()
	{
		$request = $this->getMock('\KZ\controller\Request', ['getScriptName'], ['']);
		$request->setDefaultRoute('my/default/action');
		$this->assertEquals('my', $request->getControllerPath());
		$this->assertEquals('default', $request->getController());
		$this->assertEquals('action', $request->getAction());

		$request = $this->getMock('\KZ\controller\Request', ['getScriptName'], ['//']);
		$this->assertEquals('', $request->getControllerPath());
		$this->assertEquals('index', $request->getController());
		$this->assertEquals('index', $request->getAction());
	}
}
 
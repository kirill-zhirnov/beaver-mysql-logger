<?php

namespace KZ;

class LinkTest extends \PHPUnit_Framework_TestCase
{
	public function testBasic()
	{
		$params = [
			'a' => 'b',
			'c' => 'd'
		];

		$link = new Link('test/action', $params);
		$this->assertEquals('test/action', $link->getRoute());
		$this->assertEquals($params, $link->getParams());

		$link->setParams([
			'c' => 1,
			'e' => 2
		]);
		$this->assertEquals([
			'a' => 'b',
			'c' => 1,
			'e' => 2
		], $link->getParams());

		$url = '?r=test%2Faction&a=b&c=1&e=2';
		$this->assertEquals($url, $link->getLink());
		$this->assertEquals($url, $link->__toString());

		$link->setScriptName('/htdocs/test.php');
		$this->assertEquals('/htdocs/test.php', $link->getScriptName());
		$this->assertEquals('/htdocs/test.php' . $url, $link->getLink());

		$link->clearParams();
		$this->assertEquals([], $link->getParams());
	}

	public function testSettingRequest()
	{
		$request = $this->makeRequest();
		$request
			->expects($this->once())
			->method('getScriptName')
			->will($this->returnValue('/htdocs/test.php'))
		;

		$link = new Link('test');
		$link->setRequest($request);

		$this->assertEquals('/htdocs/test.php?r=test', $link->getLink());
	}

	/**
	 * @return controller\interfaces\Request
	 */
	protected function makeRequest()
	{
		return $this->getMock('\KZ\controller\Request', ['getScriptName', 'isAjaxRequest']);
	}
}
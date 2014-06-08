<?php

namespace KZ\controller;

class ChainTest extends \PHPUnit_Framework_TestCase
{
	public function testConstructor()
	{
		$data = [
			['instance' => $this->getControllerMock(), 'action' => 'test1'],
			['instance' => $this->getControllerMock(), 'action' => 'test2'],
			['instance' => $this->getControllerMock(), 'action' => 'test3'],
		];

		$chain = new Chain($data);

		$this->assertChain($chain, $data);
	}

	public function testPrepareDataItem()
	{
		$controllerMock = $this->getControllerMock();

		$chain = new Chain();
		$this->assertEquals(
			['instance' => $controllerMock, 'action' => 'test'],
			$chain->prepareDataItem($controllerMock, 'test')
		);
	}

	public function testPush()
	{
		$data = [
			['instance' => $this->getControllerMock(), 'action' => 'test1'],
			['instance' => $this->getControllerMock(), 'action' => 'test2'],
			['instance' => $this->getControllerMock(), 'action' => 'test3'],
		];

		$chain = new Chain();
		foreach ($data as $item)
			$chain->push($item['instance'], $item['action']);

		$this->assertChain($chain, $data);
	}

	public function testAfterCurrent()
	{
		$data = [
			['instance' => $this->getControllerMock(), 'action' => 'test1'],
			['instance' => $this->getControllerMock(), 'action' => 'test2'],
		];

		$newItem = ['instance' => $this->getControllerMock(), 'action' => 'newAction'];

		$chain = new Chain($data);
		foreach ($chain as $key => $controller) {
			if ($key == 0)
				$chain->pushAfterCurrent($newItem['instance'], $newItem['action']);
		}

		$this->assertChain($chain, [
			['instance' => $data[0]['instance'], 'action' => $data[0]['action']],
			['instance' => $newItem['instance'], 'action' => $newItem['action']],
			['instance' => $data[1]['instance'], 'action' => $data[1]['action']],
		]);
	}

	public function testPushAfterPositionEmpty()
	{
		$data = [
			['instance' => $this->getControllerMock(), 'action' => 'test1'],
		];

		$chain = new Chain();
		$chain->pushAtPosition(0, $data[0]['instance'], $data[0]['action']);

		$this->assertChain($chain, $data);
	}

	public function testPushAfterPosition()
	{
		$data = [
			['instance' => $this->getControllerMock(), 'action' => 'test1'],
			['instance' => $this->getControllerMock(), 'action' => 'test2'],
		];

		$newItem = ['instance' => $this->getControllerMock(), 'action' => 'newAction'];

		$chain = new Chain($data);
		$chain->pushAtPosition(1, $newItem['instance'], $newItem['action']);

		$this->assertChain($chain, [
			['instance' => $data[0]['instance'], 'action' => $data[0]['action']],
			['instance' => $newItem['instance'], 'action' => $newItem['action']],
			['instance' => $data[1]['instance'], 'action' => $data[1]['action']],
		]);
	}

	public function testPushOnline()
	{
		$data = [
			['instance' => $this->getControllerMock(), 'action' => 'test1'],
			['instance' => $this->getControllerMock(), 'action' => 'test2'],
		];

		$newItem = ['instance' => $this->getControllerMock(), 'action' => 'newAction'];

		$chain = new Chain($data);
		foreach ($chain as $key => $controller) {
			if ($key == 0)
				$chain->pushAfterCurrent($newItem['instance'], $newItem['action']);
			elseif ($key == 1) {
				$this->assertEquals($newItem['instance'], $controller['instance']);
				$this->assertEquals($newItem['action'], $controller['action']);
			}
		}
	}

	protected function assertChain(Chain $chain, $data)
	{
		foreach ($chain as $key => $controller) {
			$this->assertEquals($data[$key]['instance'], $controller['instance']);
			$this->assertEquals($data[$key]['action'], $controller['action']);
		}
	}

	/**
	 * @return \KZ\Controller
	 */
	protected function getControllerMock()
	{
		return $this->getMockBuilder('\KZ\Controller')
			->disableOriginalConstructor()
			->getMock()
		;
	}
}
 
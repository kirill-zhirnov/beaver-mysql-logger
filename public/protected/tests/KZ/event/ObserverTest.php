<?php

namespace KZ\event;

class ObserverTest extends \PHPUnit_Framework_TestCase
{
	public function testBindEvents()
	{
		$events = [
			[
				'Test',
				'test',
				function() {return 'a';}
			],
			[
				'Test',
				'test',
				function() {return 'b';}
			],
		];

		$observer = new Observer($events);
		foreach ($observer->getListeners('Test', 'test') as $key => $event) {
			$this->assertEquals(
				call_user_func($events[$key][2]),
				call_user_func($event)
			);
		}

		$observer->unbind();
		$this->assertEquals([], $observer->getListeners());
		$this->assertEquals(false, $observer->getListeners('Test'));
		$this->assertEquals(false, $observer->getListeners('Test', 'test'));
		$this->assertEquals(false, $observer->getListeners('Test', 'test', 0));

		$observer->bindEvents($events);
		$this->assertInternalType('callable', $observer->getListeners('Test', 'test', 0));
		$this->assertInternalType('callable', $observer->getListeners('Test', 'test', 1));
		$this->assertInternalType('array', $observer->getListeners('Test', 'test'));
		$this->assertInternalType('array', $observer->getListeners('Test'));

		foreach ($observer->getListeners('Test') as $key => $val) {
			$this->assertEquals('test', $key);
			foreach ($val as $eventKey => $event) {
				$this->assertEquals(
					call_user_func($events[$eventKey][2]),
					call_user_func($event)
				);
			}
		}
	}

	public function testTrigger()
	{
		$called = 0;
		$sender = new \stdClass;

		$observer = new Observer([
			[
				'Test',
				'test',
				function($event) use($sender, &$called) {
					$this->assertInstanceOf('\KZ\event\interfaces\Event', $event);
					$this->assertFalse($event->isDefaultPrevented());
					$event->preventDefault();
					$this->assertTrue($event->isDefaultPrevented());
					$this->assertEquals($sender, $event->getSender());
					$this->assertEquals('b', $event->getParam('a'));
					$called++;
				}
			],
			[
				'Test',
				'test',
				function($event) use($sender, &$called) {
					$this->assertInstanceOf('\KZ\event\interfaces\Event', $event);
					$this->assertTrue($event->isDefaultPrevented());
					$this->assertEquals($sender, $event->getSender());
					$called++;
				}
			],
		]);


		$event = $observer->trigger('Test', 'test', $sender, ['a' => 'b']);
		$this->assertInstanceOf('\KZ\event\interfaces\Event', $event);
		$this->assertTrue($event->isDefaultPrevented());
		$this->assertEquals(2, $called);
	}

	public function testBindByObject()
	{
		$stdMock = $this->getMock('\StdClass', ['test']);
		$stdMock
			->expects($this->once())
			->method('test')
		;
		$callback = function() use($stdMock) {
			$stdMock->test();
		};

		$obj = new \StdClass;
		$observer = new Observer([
			[$obj, 'test', $callback]
		]);
		$observer->trigger($obj, 'test', $obj);
	}

	/**
	 * We test here, that events will be binded to "stdClass" name, not Mock_****
	 */
	public function testParentClass()
	{
		$stdMock = $this->getMock('\StdClass', ['test']);
		$stdMock
			->expects($this->exactly(2))
			->method('test')
		;
		$callback = function() use($stdMock) {
			$stdMock->test();
		};

		$sender = $this->getMock('StdClass');

		$observer = new Observer([
			[$sender, 'test', $callback]
		]);
		$observer->trigger('stdClass', 'test', $sender);
		$observer->trigger($sender, 'test', $sender);
	}
}
 
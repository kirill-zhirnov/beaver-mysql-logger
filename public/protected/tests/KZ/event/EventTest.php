<?php

namespace KZ\event;

class EventTest extends \PHPUnit_Framework_TestCase
{
	public function testBasic()
	{
		$sender = new \stdClass();

		$event = new Event($sender, [
			'a' => 'b',
			'c' => 'd'
		]);

		$this->assertFalse($event->isDefaultPrevented());
		$event->preventDefault();

		$this->assertTrue($event->isDefaultPrevented());
		$this->assertTrue($event->hasParam('a'));
		$this->assertTrue($event->hasParam('c'));

		$this->assertEquals('b', $event->getParam('a'));
		$this->assertEquals('d', $event->getParam('c'));
	}
}
 
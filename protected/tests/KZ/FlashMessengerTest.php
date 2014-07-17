<?php

namespace KZ;

class FlashMessengerTest extends \PHPUnit_Framework_TestCase
{
	public function testBasic()
	{
		$messenger = new FlashMessenger([
			'sessionPrefix' => 'test'
		]);
		$this->assertEquals('test', $messenger->getSessionPrefix());
		$this->assertTrue(isset($_SESSION['test']));

		$this->assertEquals(0, $messenger->add('success msg'));
		$this->assertEquals(1, $messenger->add('error msg', FlashMessenger::ERROR));

		foreach ($messenger as $type => $text) {
			if ($type == FlashMessenger::SUCCESS) {
				$this->assertEquals('success msg', $text);
			} else {
				$this->assertEquals('error msg', $text);
			}
		}

		$messenger->delete(0);
		$messenger->delete(1);

		$noItems = true;
		foreach ($messenger as $type => $text)
			$noItems = false;

		$this->assertTrue($noItems);
	}

	public function testAutoRemoving()
	{
		$messenger = new FlashMessenger();
		$messenger->add('test');

		$messenger = new FlashMessenger();
		$itemsExists = false;
		foreach ($messenger as $type => $text)
			$itemsExists = true;

		$this->assertTrue($itemsExists);

		$messenger = new FlashMessenger();
		$itemsExists = false;
		foreach ($messenger as $type => $text)
			$itemsExists = true;

		//on third time, no items should be.
		$this->assertFalse($itemsExists);
	}

	public function testExtendLivePeriod()
	{
		$messenger = new FlashMessenger();
		$messenger->add('test');

		$messenger = new FlashMessenger();
		$messenger->extend();

		$messenger = new FlashMessenger();

		$itemsExists = false;
		foreach ($messenger as $type => $text)
			$itemsExists = true;

		$this->assertTrue($itemsExists);
	}
}
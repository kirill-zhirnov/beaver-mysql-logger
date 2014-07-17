<?php

namespace KZ\view;

class HelperKitTest extends \PHPUnit_Framework_TestCase
{
	public function testBasic()
	{
		$config = [
			'helpers' => [
				'a' => 'b'
			]
		];

		$helperKit = new HelperKit($config);
		$this->assertEquals($config, $helperKit->getConfig());
	}

	public function testHelper()
	{
		$helper = $this->makeHelper();

		$helperKit = new HelperKit();
		$helperKit->setConfig([
			'helpers' => [
				'test' => get_class($helper)
			]
		]);

		$this->assertTrue($helperKit->helperExists('test'));
		$this->assertEquals(get_class($helper), $helperKit->getHelperClass('test'));

		$helperInstance = $helperKit->getHelper('test');
		$this->assertInstanceOf(get_class($helper), $helperInstance);

		$this->assertEquals($helperInstance, $helperKit->getHelper('test'));
	}

	public function testException()
	{
		$helperKit = new HelperKit([
			'helpers' => [
				'test' => 'stdClass'
			]
		]);

		$this->setExpectedException('RuntimeException',  '"test" must be instance \KZ\view\interfaces\Helper.');
		$helperKit->getHelper('test');
	}

	/**
	 * @return interfaces\Helper
	 */
	protected function makeHelper()
	{
		return $this->getMock('\KZ\view\interfaces\Helper');
	}
}
 
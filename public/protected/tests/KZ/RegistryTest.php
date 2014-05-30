<?php

class RegistryTest extends PHPUnit_Framework_TestCase
{
	public function testArrayAccess()
	{
		$registry = new \KZ\Registry();
		$registry['a'] = 'b';
		$registry['c'] = 'd';

		$this->assertTrue(isset($registry['a'], $registry['c']));
		$this->assertEquals('b', $registry['a']);
		$this->assertEquals('d', $registry['c']);

		unset($registry['a']);

		$this->assertFalse(isset($registry['a']));
		$this->assertTrue(isset($registry['c']));
		$this->assertEquals('d', $registry['c']);
	}

	public function testKeyValidator()
	{
		$registry = new \KZ\Registry();

		//проверить исключения + интегрировать задачи с PhpStorm!
	}
}
 
<?php

namespace KZ;

class RegistryTest extends \PHPUnit_Framework_TestCase
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

		$this->setExpectedException('OutOfBoundsException', 'Key should be right PHP variable name ("0a")');
		$registry['0a'] = 'a';

		$this->setExpectedException('OutOfBoundsException', 'Key should be right PHP variable name ("0")');
		$registry[0] = 'a';
	}

	public function testPropSetGet()
	{
		$registry = new \KZ\Registry();
		$registry->a = 'b';
		$registry->c = 'd';

		$this->assertEquals('b', $registry->a);
		$this->assertEquals('d', $registry->c);
	}

	public function testSetGetMethods()
	{
		$stub = $this->getMock('\KZ\Registry', ['getA', 'setA']);

		//setters
		$stub->expects($this->once())
			->method('setA')
			->with($this->identicalTo('b'))
		;
		$stub->a = 'b';

		//getters
		$stub->expects($this->once())
			->method('getA')
			->will($this->returnValue('new value'))
		;

		$this->assertEquals($stub->a, 'new value');
	}

	public function testIterator()
	{
		$data = [
			'a' => 'b',
			'c' => 'd'
		];
		$registry = new \KZ\Registry($data);

		$i = 0;
		foreach ($registry as $key => $val) {
			$this->assertTrue(isset($data[$key]));
			$this->assertEquals($val, $data[$key]);
			$i++;
		}

		$this->assertEquals(sizeof($data), $i);
	}

	public function testSetDataViaMethods()
	{
		$registry = $this->getMock('\KZ\Registry', ['setA']);

		$registry
			->expects($this->once())
			->method('setA')
		;

		$this->assertTrue($registry->isSetDataViaMethods());
		$registry['a'] = 'a';

		$registry = $this->getMock('\KZ\Registry', ['setB']);
		$registry
			->expects($this->never())
			->method('setB')
		;
		$registry->setDataViaMethods(false);
		$this->assertFalse($registry->isSetDataViaMethods());
		$registry['b'] = 'c';
	}
}

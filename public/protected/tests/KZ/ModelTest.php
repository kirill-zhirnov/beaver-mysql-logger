<?php

namespace KZ;

class ModelTest extends \PHPUnit_Framework_TestCase
{
	public function testRules()
	{
		$model = $this->makeModelMockup();

		$this->assertEquals([
			'name' => null,
			'surname' => null
		], $model->getAttributes());
		$this->assertEquals(['name', 'surname'], $model->getAttrNames());

		//errors must be empty
		$this->assertEquals([], $model->getErrors());
		$this->assertEquals([], $model->getErrors('name'));

		//set values
		$this->assertEquals($model, $model->setAttributes([
			'name' => 'Ivan',
			'surname' => 'Ivanov',
			'a' => 'b'
		]));
		$this->assertEquals([
			'name' => 'Ivan',
			'surname' => 'Ivanov',
		], $model->getAttributes());
	}

	public function testManageErrors()
	{
		$model = $this->makeModelMockup();
		$model->addError('name', 'this is error text');

		$this->assertEquals(['this is error text'], $model->getErrors('name'));
		$this->assertEquals(['name' => ['this is error text']], $model->getErrors());

		$model->clearErrors();

		$this->assertEquals([], $model->getErrors('name'));
		$this->assertEquals([], $model->getErrors());
	}

	public function testNotExistAddError()
	{
		$model = $this->makeModelMockup();

		$this->setExpectedException('OutOfBoundsException', 'Attribute "test" does not exist!');
		$model->addError('test', 'error text');
	}

	public function testNotExistClearErrors()
	{
		$model = $this->makeModelMockup();

		$this->setExpectedException('OutOfBoundsException', 'Attribute "test" does not exist!');
		$model->clearErrors('test');
	}

	public function testGetErrors()
	{
		$model = $this->makeModelMockup();

		$this->setExpectedException('OutOfBoundsException', 'Attribute "test" does not exist!');
		$model->getErrors('test');
	}

	public function testSave()
	{
		$this->setExpectedException('RuntimeException', 'Method is not implemented!');

		$model = $this->makeModelMockup();
		$model->save();
	}

	public function testValidationFalse()
	{
		$model = $this->makeModelMockup([
			'name' => [['validateName', 'option1' => 'val1', 'option2' => 'val2']],
			'surname' => []
		], ['rules', 'validateName']);

		$model
			->expects($this->once())
			->method('validateName')
			->with($this->equalTo('name'), $this->equalTo([
				'option1' => 'val1',
				'option2' => 'val2'
			]))
			->will($this->returnCallback(function($attribute, $options) use($model) {
				$model->addError($attribute, 'text');
			}))
		;

		$this->assertFalse($model->validate());
	}

	public function testValidationTrue()
	{
		$model = $this->makeModelMockup([
			'name' => [['validateName']],
			'surname' => []
		], ['rules', 'validateName']);

		$model
			->expects($this->once())
			->method('validateName')
			->with($this->equalTo('name'))
			->will($this->returnCallback(function($attribute, $options) use($model) {
			}))
		;

		$this->assertTrue($model->validate());
	}

	/**
	 * @param array $rules
	 * @param array $methods
	 * @param bool $rulesExpects
	 * @return Model
	 */
	protected function makeModelMockup(array $rules = null, array $methods = ['rules'], $rulesExpects = false)
	{
		if ($rulesExpects === false)
			$rulesExpects = $this->any();

		if (is_null($rules))
			$rules = [
				'name' => [],
				'surname' => []
			];

		$model = $this->getMock('\KZ\Model', $methods);

		$model
			->expects($rulesExpects)
			->method('rules')
			->will($this->returnValue($rules))
		;

		foreach (array_keys($rules) as $attr)
			$model->{$attr} = null;

		return $model;
	}
}
 
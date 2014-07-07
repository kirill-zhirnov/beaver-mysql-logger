<?php

namespace KZ;

abstract class Model implements model\interfaces\Model
{
	/**
	 * Errors.
	 *
	 * @var array
	 */
	protected $errors = [];

	/**
	 * Attribute names.
	 *
	 * @var array
	 */
	protected $attrNames = [];

	/**
	 * Attributes default values.
	 *
	 * @var array
	 */
	protected $defaultValues = [];

	public function __construct()
	{
		$this->setupDefaultValues();
	}

	/**
	 * Set values to model's attributes.
	 *
	 * @param array $attributes
	 * @return $this
	 */
	public function setAttributes(array $attributes)
	{
		foreach ($this->getAttrNames() as $attrName)
			if (isset($attributes[$attrName]))
				$this->{$attrName} = $attributes[$attrName];

		return $this;
	}

	/**
	 * Returns array with attributes values.
	 *
	 * @return array
	 */
	public function getAttributes()
	{
		$out = [];
		foreach ($this->getAttrNames() as $attr)
			$out[$attr] = isset($this->{$attr}) ? $this->{$attr} : null;

		return $out;
	}

	/**
	 * Validate attributes.
	 *
	 * @throws \UnexpectedValueException
	 * @return boolean
	 */
	public function validate()
	{
		$this->clearErrors();

		foreach ($this->rules() as $attribute => $validators) {
			foreach ($validators as $validator) {
				if (!isset($validator[0]))
					throw new \UnexpectedValueException('Validator row must contain method name at first position.');

				if (!method_exists($this, $validator[0]))
					throw new \UnexpectedValueException('Method "' . $validator[0] . '" does not exist.');

				call_user_func_array([$this, $validator[0]], array_merge([$attribute], [array_slice($validator, 1)]));
			}
		}

		return $this->errors ? false : true;
	}

	/**
	 * Save changes.
	 *
	 * @throws \RuntimeException
	 * @return $this
	 */
	public function save()
	{
		throw new \RuntimeException('Method is not implemented!');
	}

	/**
	 * Return array with attribute names.
	 *
	 * @return array
	 */
	public function getAttrNames()
	{
		if (!$this->attrNames)
			$this->attrNames = array_keys($this->rules());

		return $this->attrNames;
	}

	/**
	 * Add error to attribute.
	 *
	 * @param string $attribute
	 * @param string $error
	 * @throws \OutOfBoundsException
	 * @return $this
	 */
	public function addError($attribute, $error)
	{
		$this->checkAttrName($attribute);

		if (!isset($this->errors[$attribute]))
			$this->errors[$attribute] = [];

		$this->errors[$attribute][] = $error;

		return $this;
	}

	/**
	 * Clear errors for attribute or for all attributes.
	 *
	 * @param string $attribute
	 * @return $this
	 */
	public function clearErrors($attribute = null)
	{
		if ($attribute) {
			$this->checkAttrName($attribute);

			if (isset($this->errors[$attribute]))
				$this->errors[$attribute] = [];
		} else {
			$this->errors = [];
		}

		return $this;
	}

	/**
	 * Return all errors or errors for given attribute.
	 *
	 * @param string $attribute
	 * @return array
	 */
	public function getErrors($attribute = null)
	{
		if ($attribute) {
			$this->checkAttrName($attribute);

			return isset($this->errors[$attribute]) ? $this->errors[$attribute] : [];
		} else {
			return $this->errors;
		}
	}

	/**
	 * @param string $attribute
	 * @return boolean
	 */
	public function hasAttribute($attribute)
	{
		return (in_array($attribute, $this->getAttrNames()) && property_exists($this, $attribute));
	}

	/**
	 * Returns attribute value.
	 *
	 * @param string $attribute
	 * @return mixed
	 */
	public function getAttribute($attribute)
	{
		$this->checkAttrName($attribute);

		return $this->{$attribute};
	}

	public function required($attribute)
	{
		if (trim($this->getAttribute($attribute)) == '')
			$this->addError($attribute, 'Value cannot be blank.');
	}

	public function getDefaultValue($attribute)
	{
		$this->checkAttrName($attribute);

		return $this->defaultValues[$attribute];
	}

	/**
	 * If attribute does not exist - exception will be thrown.
	 *
	 * @param $attribute
	 * @throws \OutOfBoundsException
	 */
	protected function checkAttrName($attribute)
	{
		if (!$this->hasAttribute($attribute))
			throw new \OutOfBoundsException('Attribute "' . $attribute . '" does not exist!');
	}

	protected function setupDefaultValues()
	{
		foreach ($this->getAttrNames() as $attr)
			$this->defaultValues[$attr] = $this->{$attr};
	}
}
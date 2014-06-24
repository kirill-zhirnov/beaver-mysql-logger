<?php

namespace KZ\model\interfaces;

interface Model
{
	/**
	 * Returns array with rules.
	 * Example of rule array:
	 * <code>
	 * <?php
	 * return [
	 *     'attribute' => [
	 *         ['methodName', 'option' => 'value'] //options are not necessary
	 *     ],
	 *     'attrNoRules' => [] //attributes without rules
	 * ];
	 * </code>
	 *
	 * @return array
	 */
	public function rules();

	/**
	 * Set values to model's attributes.
	 *
	 * @param array $attributes
	 * @return $this
	 */
	public function setAttributes(array $attributes);

	/**
	 * Returns array with attributes values.
	 *
	 * @return array
	 */
	public function getAttributes();

	/**
	 * Validate attributes.
	 *
	 * @return boolean
	 */
	public function validate();

	/**
	 * Save changes.
	 *
	 * @return $this
	 */
	public function save();

	/**
	 * Return all errors or errors for given attribute.
	 *
	 * @param string $attribute
	 * @return array
	 */
	public function getErrors($attribute = null);

	/**
	 * Clear errors for attribute or for all attributes.
	 *
	 * @param string $attribute
	 * @return $this
	 */
	public function clearErrors($attribute = null);

	/**
	 * Add error to attribute.
	 *
	 * @param string $attribute
	 * @param string $error
	 * @return $this
	 */
	public function addError($attribute, $error);

	/**
	 * Return array with attribute names.
	 *
	 * @return array
	 */
	public function getAttrNames();

	/**
	 * @param string $attribute
	 * @return boolean
	 */
	public function hasAttribute($attribute);

	/**
	 * Returns attribute value.
	 *
	 * @param string $attribute
	 * @return mixed
	 */
	public function getAttribute($attribute);
} 
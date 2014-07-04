<?php

namespace KZ\view\helpers;

use KZ\view\interfaces as viewInterfaces,
	KZ\model\interfaces as modelInterfaces,
	KZ\view
;

class Html extends view\Helper
{
	/**
	 * Generates <label for="id">text</label>
	 *
	 * @param $model
	 * @param $attribute
	 * @param $text
	 * @param array $htmlAttributes
	 * @return string
	 */
	public function label($model, $attribute, $text, array $htmlAttributes = [])
	{
		$htmlAttributes = array_replace([
			'for' => $this->id($model, $attribute),
			'class' => 'control-label',
		], $htmlAttributes);

		return '<label ' .  $this->getTagAttrs($htmlAttributes) . '>' . $this->encode($text) . '</label>';
	}

	/**
	 * Generates <input type="text" />.
	 *
	 * @param object|string $model
	 * @param string $attribute
	 * @param array $htmlAttributes
	 * @return string
	 */
	public function text($model, $attribute, array $htmlAttributes = [])
	{
		$htmlAttributes = array_replace([
			'name' => $this->name($model, $attribute),
			'value' => $this->value($model, $attribute, false),
			'type' => 'text',
			'id' => $this->id($model, $attribute)
		], $htmlAttributes);

		return '<input ' . $this->getTagAttrs($htmlAttributes) . ' />';
	}

	/**
	 * Generates <textarea></textarea>.
	 *
	 * @param object|string $model
	 * @param $attribute
	 * @param array $htmlAttributes
	 * @return string
	 */
	public function textArea($model, $attribute, array $htmlAttributes = [])
	{
		$htmlAttributes = array_replace([
			'name' => $this->name($model, $attribute),
			'id' => $this->id($model, $attribute)
		], $htmlAttributes);

		return '<textarea ' . $this->getTagAttrs($htmlAttributes) . '>' . $this->value($model, $attribute) . '</textarea>';
	}

	/**
	 * Generates list of errors for given attribute.
	 *
	 * @param $model
	 * @param $attribute
	 * @param array $htmlAttributes
	 * @return string
	 */
	public function errors($model, $attribute, array $htmlAttributes = [])
	{
		$htmlAttributes = array_replace([
			'class' => 'errors'
		], $htmlAttributes);

		if (!$model instanceof modelInterfaces\Model
			|| !$model->hasAttribute($attribute)
			|| !$errors = $model->getErrors($attribute)
		)
			return;

		$out = '<ul ' . $this->getTagAttrs($htmlAttributes) . '>';
		foreach ($errors as $error)
			$out .= '<li>' . $error . '</li>';

		$out .= '</ul>';

		return $out;
	}

	public function getTagAttrs(array $attributes)
	{
		$out = [];
		foreach ($attributes as $key => $value)
			$out[] = $this->encode($key) . '="' . $this->encode($value) . '"';

		return implode(' ', $out);
	}

	public function encode($value)
	{
		return htmlspecialchars($value, \ENT_QUOTES);
	}

	/**
	 * Get attribute value.
	 *
	 * @param $model
	 * @param $attribute
	 * @param bool $encode
	 * @return string
	 */
	public function value($model, $attribute, $encode = true)
	{
		if ($model instanceof modelInterfaces\Model && $model->hasAttribute($attribute)) {
			$value = $model->getAttribute($attribute);

			return $encode ? $this->encode($value) : $value;
		}

		return '';
	}

	/**
	 * Generates name by $model and attribute.
	 *
	 * @param object|string $model
	 * @param $attribute
	 * @return string
	 */
	public function name($model, $attribute)
	{
		$className = $this->getModelPrefix($model);

		if (preg_match('#^([^\[\]]+)(\[.+)$#i', $attribute, $matches))
			return $className . '[' . $matches[1] . ']' . $matches[2];
		else
			return $className . '[' . $attribute . ']';

	}

	/**
	 * Generates id by $model and attribute.
	 *
	 * @param object|string $model
	 * @param $attribute
	 * @return string
	 */
	public function id($model, $attribute)
	{
		$className = $this->getModelPrefix($model);

		$attribute = strtr($attribute, [
			'[' => '_',
			']' => ''
		]);

		return $className . '_' . $attribute;
	}

	/**
	 * Returns prefix for model
	 *
	 * @param object|string $model
	 * @return string
	 */
	public function getModelPrefix($model)
	{
		$model = (is_object($model)) ? get_class($model) : $model;
		return str_replace('\\', '_', $model);
	}

	/**
	 * Generates open tag and add special classes in case of error:
	 * <div class="form-group">
	 *
	 * @param $model
	 * @param $attribute
	 * @param string $classes
	 * @param array $htmlAttributes
	 * @return string
	 */
	public function formGroup($model, $attribute, $classes = '', array $htmlAttributes = [])
	{
		$htmlAttributes = array_replace([
			'class' => 'form-group ' . $classes
		], $htmlAttributes);

		if ($model instanceof modelInterfaces\Model
			&& $model->hasAttribute($attribute)
			&& $model->getErrors($attribute)
		)
			$htmlAttributes['class'] .= ' has-error';

		return '<div ' . $this->getTagAttrs($htmlAttributes) . '>';
	}
}
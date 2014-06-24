<?php

namespace KZ\view\helpers;
use KZ\model\interfaces\Model;
use KZ\view\interfaces;

class Html implements interfaces\Helper
{
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
			'type' => 'text'
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
			'name' => $this->name($model, $attribute)
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

		if (!$model instanceof Model
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
		if ($model instanceof Model && $model->hasAttribute($attribute)) {
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
}
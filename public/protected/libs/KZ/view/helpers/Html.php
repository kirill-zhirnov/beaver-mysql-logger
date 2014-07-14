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
	 * @param modelInterfaces\Model|string $model
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
	 * @param modelInterfaces\Model|string $model
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

	public function dropDownList($model, $attribute, array $list = [], array $htmlAttributes = [])
	{
		$htmlAttributes = array_replace([
			'name' => $this->name($model, $attribute),
			'value' => $this->value($model, $attribute, false),
			'id' => $this->id($model, $attribute)
		], $htmlAttributes);

		$value = $htmlAttributes['value'];
		unset($htmlAttributes['value']);

		$out = '<select ' . $this->getTagAttrs($htmlAttributes) . '>';
		foreach ($list as $optVal => $label) {
			$attrs = ['value' => $optVal];

			if ($optVal == $value)
				$attrs['selected'] = 'selected';

			$out .= '<option ' . $this->getTagAttrs($attrs) . '>' . $label . '</option>';
		}

		$out .= '</select>';

		return $out;
	}

	/**
	 * Generates <textarea></textarea>.
	 *
	 * @param modelInterfaces\Model|string $model
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
	 * @param modelInterfaces\Model|string $model
	 * @param $attribute
	 * @param array $htmlAttributes
	 * @return string
	 */
	public function errors($model, $attribute, array $htmlAttributes = [])
	{
		$htmlAttributes = array_replace([
			'class' => 'errors'
		], $htmlAttributes);

		if (!$model->hasAttribute($attribute) || !$errors = $model->getErrors($attribute))
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
	 * @param modelInterfaces\Model|string $model
	 * @param $attribute
	 * @param bool $encode
	 * @return string
	 */
	public function value($model, $attribute, $encode = true)
	{
		if ($model->hasAttribute($attribute)) {
			$value = $model->getAttribute($attribute);

			return $encode ? $this->encode($value) : $value;
		}

		return '';
	}

	/**
	 * Generates name by $model and attribute.
	 *
	 * @param modelInterfaces\Model|string $model
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
	 * @param modelInterfaces\Model|string $model
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
	 * @param modelInterfaces\Model|string $model
	 * @return string
	 */
	public function getModelPrefix($model)
	{
		return ($model instanceof modelInterfaces\Model)
			? $model->getLinkPrefix() : \KZ\Model::getModelPrefix($model);
	}

	/**
	 * Generates open tag and add special classes in case of error:
	 * <div class="form-group">
	 *
	 * @param modelInterfaces\Model $model
	 * @param $attribute
	 * @param string $classes
	 * @param array $htmlAttributes
	 * @return string
	 */
	public function formGroup(modelInterfaces\Model $model, $attribute, $classes = '', array $htmlAttributes = [])
	{
		$htmlAttributes = array_replace([
			'class' => 'form-group ' . $classes
		], $htmlAttributes);

		if ($model->hasAttribute($attribute) && $model->getErrors($attribute))
			$htmlAttributes['class'] .= ' has-error';

		return '<div ' . $this->getTagAttrs($htmlAttributes) . '>';
	}
}
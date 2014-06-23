<?php

namespace KZ\view\helpers;
use KZ\model\interfaces\Model;
use KZ\view\interfaces;

class Html implements interfaces\Helper
{
	/**
	 * Generates name by $model and attribute.
	 *
	 * @param Model $model
	 * @param $attribute
	 * @return string
	 */
	public function name(Model $model, $attribute)
	{
		return '';
	}
}
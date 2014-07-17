<?php

namespace KZ\model;


abstract class Filter extends \KZ\Model implements interfaces\Filter
{
	/**
	 * Prepare filters:
	 * If attributes has errors default value will be set for this attribute.
	 *
	 * @return $this
	 */
	public function makeFilters()
	{
		foreach ($this->getAttrNames() as $attr)
			if ($this->getErrors($attr))
				$this->{$attr} = $this->getDefaultValue($attr);
	}
}
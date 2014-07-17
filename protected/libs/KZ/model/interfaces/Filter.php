<?php

namespace KZ\model\interfaces;

interface Filter extends  Model
{
	/**
	 * Prepare filters:
	 * If attributes has errors default value will be set for this attribute.
	 *
	 * @return $this
	 */
	public function makeFilters();
}
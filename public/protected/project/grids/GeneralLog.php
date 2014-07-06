<?php

namespace grids;
use KZ\grid;
use KZ\grid\interfaces\Pager;

class GeneralLog extends grid\Grid
{
	/**
	 * @var
	 */
	protected $filter;

	/**
	 * @return array
	 */
	public function getRows()
	{
		return [];
	}

	/**
	 * @return Pager
	 */
	public function getPager()
	{
	}
} 
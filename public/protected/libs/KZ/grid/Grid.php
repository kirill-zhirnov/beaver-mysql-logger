<?php

namespace KZ\grid;


abstract class Grid implements interfaces\Grid
{
	/**
	 * @var \KZ\app\Registry
	 */
	protected $registry;

	/**
	 * @param \KZ\app\Registry $registry
	 */
	public function __construct(\KZ\app\Registry $registry)
	{
		$this->registry = $registry;
	}

	/**
	 * @return \KZ\app\Registry
	 */
	public function getRegistry()
	{
		return $this->registry;
	}
}
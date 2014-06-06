<?php

namespace KZ;

abstract class Controller
{
	/**
	 * @var controller\Front
	 */
	protected $frontController;

	public function __construct(controller\Front $frontController)
	{
		$this->frontController = $frontController;
	}
} 
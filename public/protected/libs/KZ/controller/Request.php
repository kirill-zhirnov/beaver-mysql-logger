<?php

namespace KZ\controller;
use KZ\controller;

abstract class Request implements controller\interfaces\Request
{
	/**
	 * @var string
	 */
	protected $route;

	/**
	 * @var string
	 */
	protected $controller;

	/**
	 * @var string
	 */
	protected $action;
//эти настройки должны быть здесь, тк request отвечает за разбор запроса!
	/**
	 * @var string
	 */
	protected $controllersPath;

	/**
	 * @var string
	 */
	protected $controllersSuffix = 'Controller';

	public function getController()
	{
		if (!$this->controller)
			$this->controller = $this->dispatchRoute($this->route);

		return $this->controller;
	}

	public function getAction()
	{}

	public function dispatchRoute($route)
	{}
} 
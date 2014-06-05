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
	 * Controller name
	 *
	 * @var string
	 */
	protected $controller;

	/**
	 * Relative path to Controller
	 *
	 * @var string
	 */
	protected $controllerPath;

	/**
	 * Action name
	 *
	 * @var string
	 */
	protected $action;

	/**
	 * Custom params.
	 *
	 * @var array
	 */
	protected $params = [];

	/**
	 * @var string
	 */
	protected $defaultRoute = 'index/index';

	public function __construct($route)
	{
		$this->route = $route;
	}

	public function getController()
	{
		if (!isset($this->controller))
			$this->dispatchRoute();

		return $this->controller;
	}

	public function getControllerPath()
	{
		if (!isset($this->controllerPath))
			$this->dispatchRoute();

		return $this->controllerPath;
	}

	public function getAction()
	{
		if (!isset($this->action))
			$this->dispatchRoute();

		return $this->action;
	}

	public function dispatchRoute()
	{
		$this->route = trim($this->route, '/');

		if ($this->route == '')
			$this->route = $this->defaultRoute;

		if (!preg_match('#^([\w\/]*)(?:^|\/)([\w]+)\/([\w]+)$#i', $this->route, $matches))
			throw new \UnexpectedValueException('Route must have at least 2 parts separated by "/".');

		$this->controllerPath = $matches[1];
		$this->controller = $matches[2];
		$this->action = $matches[3];
	}

	public function getParam($key, $default = null)
	{
		return array_key_exists($key, $this->params) ? $this->params[$key] : $default ;
	}

	public function getParams()
	{
		return $this->params;
	}

	/**
	 * @param array $params
	 * @return $this
	 */
	public function setParams(array $params)
	{
		$this->params = array_replace($this->params, $params);

		return $this;
	}

	/**
	 * @param string $defaultRoute
	 * @return $this
	 */
	public function setDefaultRoute($defaultRoute)
	{
		$this->defaultRoute = $defaultRoute;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDefaultRoute()
	{
		return $this->defaultRoute;
	}
}
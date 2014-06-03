<?php

namespace KZ\controller;
use KZ\app;

class Front
{
	protected $request;

	/**
	 * @var app\interfaces\Registry
	 */
	protected $registry;

	public function __construct($request, app\interfaces\Registry $registry)
	{
		$this->request = $request;
	}

	public function run()
	{}

	/**
	 * @param string $controllersPath
	 * @return $this
	 */
	public function setControllersPath($controllersPath)
	{
		$this->controllersPath = $controllersPath;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getControllersPath()
	{
		return $this->controllersPath;
	}

	/**
	 * @param string $controllersSuffix
	 * @return $this
	 */
	public function setControllersSuffix($controllersSuffix)
	{
		$this->controllersSuffix = $controllersSuffix;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getControllersSuffix()
	{
		return $this->controllersSuffix;
	}
} 
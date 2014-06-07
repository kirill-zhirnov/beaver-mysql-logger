<?php

namespace KZ\controller;
use KZ\app\interfaces\Registry;

class Front
{
	/**
	 * This is an Abstract Factory for Controllers instance.
	 *
	 * @var Kit
	 */
	protected $controllerKit;

	/**
	 * @var Registry
	 */
	protected $registry;

	/**
	 * @var \KZ\Controller[]
	 */
	protected $controllersChain = [];

//отнаследовать от registry, сделать toString, toJson, менейдж headers.
	protected $response;

	public function __construct(Kit $controllerKit, Registry $registry)
	{
		$this->controllerKit = $controllerKit;
		$this->registry = $registry;
	}

	public function run()
	{
		$this->makeControllersChain();

		foreach ($this->controllersChain as $controller)
			$controller['instance']->{$controller['action']}();
	}

	public function makeControllersChain()
	{
		$request = $this->registry->getRequest();

		$this->controllersChain[] = [
			'instance' => $this->controllerKit->makeController(
				$request->getControllerPath(),
				$request->getController(),
				$request->getAction()
			),

			'action' => $request->getAction()
		];
	}

	public function getControllersChain()
	{
		return $this->controllersChain;
	}

	public function forward($controllerPath, $controller, $action)
	{

	}
} 
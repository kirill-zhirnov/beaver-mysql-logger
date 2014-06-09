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
	 * @var Chain
	 */
	protected $controllerChain;

//отнаследовать от registry, сделать toString, toJson, менейдж headers.
	protected $response;

	public function __construct(Kit $controllerKit, Registry $registry)
	{
		$this->controllerKit = $controllerKit;
		$this->registry = $registry;
	}

	public function init()
	{
		if ($this->controllerChain)
			return;

		$this->makeControllerChain();
	}

	public function run()
	{
		$this->init();

		foreach ($this->controllerChain as $item)
			$item['instance']->{$item['action']}();
	}

	/**
	 * @return Chain
	 */
	public function getControllerChain()
	{
		return $this->controllerChain;
	}

	public function forward($controllerPath, $controller, $action)
	{
		if (!$this->controllerChain)
			throw new \RuntimeException('You must call init to setup controllerChain before calling this method!');

		$controller = $this->makeController(
			$controllerPath, $controller, $action
		);

		$this->controllerChain->pushAfterCurrent($controller['instance'], $controller['action']);

		return $this;
	}

	/**
	 * @return Request
	 */
	public function getRequest()
	{
		return $this->registry->getRequest();
	}

	public function makeController($controllerPath, $controller, $action)
	{
		$controller = $this->controllerKit->makeController(
			$controllerPath,
			$controller,
			$action
		);

		return [
			'instance' => $controller,
			'action' => $action
		];
	}

	protected function makeControllerChain()
	{
		$controller = $this->makeController(
			$this->getRequest()->getControllerPath(),
			$this->getRequest()->getController(),
			$this->getRequest()->getAction()
		);

		$this->controllerChain = $this->registry->getKit()->makeControllerChain();
		$this->controllerChain->push($controller['instance'], $controller['action']);
	}
} 
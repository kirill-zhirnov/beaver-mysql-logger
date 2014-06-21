<?php

namespace KZ\controller;
use KZ\app\interfaces\Registry,
	KZ\event;

/**
 * Class Front
 * @package KZ\controller
 */
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

	/**
	 * @param Kit $controllerKit
	 * @param Registry $registry
	 */
	public function __construct(Kit $controllerKit, Registry $registry)
	{
		$this->controllerKit = $controllerKit;
		$this->registry = $registry;
	}

	/**
	 * Set up internal properties.
	 */
	public function init()
	{
		if ($this->controllerChain)
			return;

		$this->controllerChain = $this->makeControllerChain();
	}

	/**
	 * Run controller chain.
	 */
	public function run()
	{
		$this->init();

		$event = $this->registry
			->getObserver()
			->trigger($this, 'beforeRunControllerChain')
		;

		//listeners can prevent running chain
		if (!$event->isDefaultPrevented())
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

	/**
	 * Add controller in controller chain after current controller.
	 *
	 * @param $controllerPath
	 * @param $controller
	 * @param $action
	 * @return $this
	 * @throws \RuntimeException
	 */
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

	/**
	 * Make controller instance.
	 *
	 * @param $controllerPath
	 * @param $controller
	 * @param $action
	 * @throws \RuntimeException
	 * @return array
	 */
	public function makeController($controllerPath, $controller, $action)
	{
		$controllerInstance = $this->controllerKit->makeController(
			$controllerPath,
			$controller,
			$action
		);

		if (!$controllerInstance)
			throw new \RuntimeException('Controller not found. Path:"' . $controllerPath . '", Controller:"' . $controller . '". Action:"' . $action . '".', 404);

		return [
			'instance' => $controllerInstance,
			'action' => $this->controllerKit->getActionMethod($action)
		];
	}

	/**
	 * @return Registry
	 */
	public function getRegistry()
	{
		return $this->registry;
	}

	/**
	 * Redirect to a given URL.
	 *
	 * @param $url
	 * @param bool $exit
	 */
	public function redirect($url, $exit = true)
	{
		header('Location: ' . $url);

		if ($exit)
			exit();
	}

	/**
	 * @param $route
	 * @param array $params
	 * @return \KZ\link\interfaces\Link
	 */
	public function makeLink($route, array $params = [])
	{
		$link = $this->registry->getKit()->makeLink($route, $params);
		$link->setRequest($this->getRequest());

		return $link;
	}

	/**
	 * Make controller chain by request.
	 *
	 * @return Chain
	 */
	protected function makeControllerChain()
	{
		$controller = $this->makeController(
			$this->getRequest()->getControllerPath(),
			$this->getRequest()->getController(),
			$this->getRequest()->getAction()
		);

		$chain = $this->registry->getKit()->makeControllerChain();
		$chain->push($controller['instance'], $controller['action']);

		return $chain;
	}
} 
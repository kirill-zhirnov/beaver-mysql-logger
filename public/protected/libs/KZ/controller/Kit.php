<?php

namespace KZ\controller;

/**
 * This is an Abstract Factory for Controllers instance. It makes Controllers by parsed route.
 *
 * @package KZ\controller
 */
class Kit
{
	/**
	 * Absolute path to directory with controllers.
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * @var string
	 */
	protected $namespacePrefix = 'controllers';

	/**
	 * Prefix for method action.
	 *
	 * @var string
	 */
	protected $actionPrefix = 'action';

	/**
	 * Front controller must be passed to Controller instance.
	 *
	 * @var Front
	 */
	protected $frontController;

	/**
	 * @param $path Absolute path to directory with controllers.
	 * @param array $config
	 */
	public function __construct($path, array $config = [])
	{
		$this->path = $path;
		$this->setConfig($config);
	}

	/**
	 * Makes controller instance. Instance will be checked that it has method
	 * for action and that instance extends from \KZ\Controller.
	 *
	 * @param $controllerPath
	 * @param $controller
	 * @param $action
	 * @return \KZ\Controller|bool
	 * @throws \RuntimeException
	 */
	public function makeController($controllerPath, $controller, $action)
	{
		if (!$this->frontController)
			throw new \RuntimeException('You must set frontController before calling this func.');

		$className = $this->createClassName($controllerPath, $controller);
		$actionName = $this->getActionMethod($action);

		if (!class_exists($className) || !method_exists($className, $actionName))
			return false;

		$class = new $className($this->frontController);
		if (!$class instanceof \KZ\Controller)
			throw new \RuntimeException('Controller must be instance of \KZ\Controller.');

		return $class;
	}

	/**
	 * Creates class name by controllerPath and controller name.
	 *
	 * @param $controllerPath
	 * @param $controller
	 * @throws \UnexpectedValueException
	 * @return string
	 */
	public function createClassName($controllerPath, $controller)
	{
		$controllerPath = trim($controllerPath, '/');
		$controllerPath = str_replace('/', '\\', $controllerPath);

		if ($this->namespacePrefix == '')
			throw new \UnexpectedValueException('Namespace prefix must be non empty by security reasons.');

		return $this->namespacePrefix . '\\' . strtolower($controllerPath) . '\\' . ucfirst(strtolower($controller));
	}

	/**
	 * Creates method name for given action.
	 *
	 * @param $action
	 * @return string
	 */
	public function getActionMethod($action)
	{
		return $this->actionPrefix . $action;
	}

	/**
	 * @return string
	 */
	public function getNamespacePrefix()
	{
		return $this->namespacePrefix;
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @return string
	 */
	public function getActionPrefix()
	{
		return $this->actionPrefix;
	}

	/**
	 * @param Front $front
	 * @return $this
	 */
	public function setFrontController(Front $front)
	{
		$this->frontController = $front;

		return $this;
	}

	protected function setConfig(array $config)
	{
		$allowOptions = [
			'namespacePrefix',
			'actionPrefix'
		];

		foreach ($config as $key => $value)
			if (in_array($key, $allowOptions))
				$this->{$key} = $value;
	}
} 
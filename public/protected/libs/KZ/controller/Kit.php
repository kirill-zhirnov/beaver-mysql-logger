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

	protected $actionPrefix = 'action';

	public function __construct($path, array $config = [])
	{
		$this->path = $path;
		$this->setConfig($config);
	}

	public function makeController($controllerPath, $controller, $action)
	{
		$className = $this->createClassName($controllerPath, $controller);
		$actionName = $this->actionPrefix . $action;

		if (!class_exists($className) || !method_exists($className, $actionName))
			return false;

//		$class = new $className();
//		if (!instanceof)
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
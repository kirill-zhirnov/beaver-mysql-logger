<?php

namespace KZ\view;

use KZ\view\interfaces\Helper;

class HelperKit implements interfaces\HelperKit
{
	/**
	 * Example:
	 * <code>
	 * <?php
	 * $config = ['helpers' => []];
	 * ?>
	 * </code>
	 * @var array
	 */
	protected $config = [];

	/**
	 * @var array
	 */
	protected $helpers = [];

	/**
	 * @param array $config
	 */
	public function __construct(array $config = [])
	{
		if ($config)
			$this->setConfig($config);
	}

	/**
	 * @param array $config
	 * @return $this
	 */
	public function setConfig(array $config)
	{
		$this->config = $config;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * Check if helper exists or not.
	 *
	 * @param $name
	 * @return boolean
	 */
	public function helperExists($name)
	{
		$name = strtolower($name);

		if (isset($this->helpers[$name]))
			return true;

		$class = $this->getHelperClass($name);

		return class_exists($class);
	}

	/**
	 * Returns helper instance.
	 *
	 * @param $name
	 * @throws \RuntimeException
	 * @return Helper
	 */
	public function getHelper($name)
	{
		$name = strtolower($name);

		if (isset($this->helpers[$name]))
			return $this->helpers[$name];

		$class = $this->getHelperClass($name);

		if (!class_exists($class))
			throw new \RuntimeException('Helper "' . $class . '" does not exist!');

		$this->helpers[$name] = new $class();

		if (!$this->helpers[$name] instanceof Helper)
			throw new \RuntimeException('"' . $name . '" must be instance \KZ\view\interfaces\Helper.');

		return $this->helpers[$name];
	}

	public function getHelperClass($name)
	{
		$name = strtolower($name);

		if (isset($this->config['helpers'][$name]))
			return $this->config['helpers'][$name];

		return '\KZ\view\helpers\\' . ucfirst($name);
	}
} 
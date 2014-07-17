<?php

namespace KZ\view;

use KZ\view\interfaces,
	KZ\app\interfaces as appInterfaces
;

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
	 * @var appInterfaces\Registry
	 */
	protected $registry;

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
		$helperKey = strtolower($name);

		if (isset($this->helpers[$helperKey]))
			return $this->helpers[$helperKey];

		$class = $this->getHelperClass($name);

		if (!class_exists($class))
			throw new \RuntimeException('Helper "' . $class . '" does not exist!');

		/** @var interfaces\Helper $helper */
		$helper = new $class();

		if (!$helper instanceof interfaces\Helper)
			throw new \RuntimeException('"' . $name . '" must be instance \KZ\view\interfaces\Helper.');

		if ($this->registry)
			$helper->setRegistry($this->registry);

		$this->helpers[$helperKey] = $helper;

		return $helper;
	}

	public function getHelperClass($name)
	{
		if (isset($this->config['helpers'][$name]))
			return $this->config['helpers'][$name];

		return '\KZ\view\helpers\\' . ucfirst($name);
	}

	/**
	 * @param appInterfaces\Registry $registry
	 * @return $this
	 */
	public function setRegistry(appInterfaces\Registry $registry)
	{
		$this->registry = $registry;

		return $this;
	}

	/**
	 * @throws \RuntimeException
	 * @return appInterfaces\Registry
	 */
	public function getRegistry()
	{
		if (!$this->registry)
			throw new \RuntimeException('You must set registry before calling this method.');

		return $this->registry;
	}
} 
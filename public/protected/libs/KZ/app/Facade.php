<?php

namespace KZ\app;

/**
 * This is Facade for application (pattern Facade). This class is abstract, since
 * can be different logic for web or console application. For this purposes pattern
 * Factory Method is used:
 * @see Facade::makeFrontController()
 *
 * Class Facade
 * @package KZ\app
 */
abstract class Facade
{
	/**
	 * @var array
	 */
	protected $config;

	/**
	 * @var bool
	 */
	protected $initialized = false;

	/**
	 * @var interfaces\Registry
	 */
	protected $registry;

	/**
	 * @var interfaces\Kit
	 */
	protected $kit;

	/**
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}

	public function run()
	{
		if (!$this->initialized)
			$this->initialize();

		//fixme: это тоже надо вынести в initialize и класть в регистр - тогда будет меньше зависимостей
		$frontController = $this->makeFrontController();
	}

	abstract public function makeFrontController();

	/**
	 * Initialize application. Make all necessary components and put it in registry.
	 *
	 * @return $this
	 */
	public function initialize()
	{
		$this->kit = $this->makeKit();

		//make components
		$this->registry = $this->kit->makeRegistry();
		$cs = $this->kit->makeConnectionStorage();

		//put components in registry
		$this->registry
			->setConnectionStorage($cs)
			->setKit($this->kit)
			->setConfig($this->config)
		;

		$this->initialized = true;

		return $this;
	}

	/**
	 * @throws \RuntimeException
	 * @return \KZ\app\interfaces\Registry
	 */
	public function getRegistry()
	{
		if (!$this->initialized)
			throw new \RuntimeException('You must call initialize method before calling this one.');

		return $this->registry;
	}

	/**
	 * @return interfaces\Kit
	 * @throws \RuntimeException
	 */
	public function makeKit()
	{
		$className = '\KZ\app\Kit';
		if (isset($this->config['components']['kit']['class']))
			$className = $this->config['components']['kit']['class'];

		$kit = new $className($this->config);

		if (!$kit instanceof interfaces\Kit)
			throw new \RuntimeException('Kit must implement interface interfaces\Kit.');

		return $kit;
	}

	/**
	 * @return bool
	 */
	public function isInitialized()
	{
		return $this->initialized;
	}
} 
<?php

namespace KZ\app;
use KZ\controller;

/**
 * This is Facade for application (pattern Facade). This class is abstract, since
 * can be different logic for web or console application. For this purposes pattern
 * Factory Method is used:
 * @see Facade::makeRequest()
 * @see Facade::makeControllerKit()
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
	 * @var controller\Front
	 */
	protected $frontController;

	/**
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * Initialize application and run controllers chain.
	 */
	public function run()
	{
		if (!$this->initialized)
			$this->initialize();

		$this->frontController->run();
	}

	/**
	 * Initialize application. Make all necessary components and put it in registry.
	 *
	 * @return $this
	 */
	public function initialize()
	{
		$this->kit = $this->makeKit();

		//make basic components
		$this->registry = $this->kit->makeRegistry();
		$this->registry
			->setConfig($this->config)
			->setKit($this->kit)
			->setObserver($this->kit->makeObserver())
		;

		//trigger event
		$event = $this->registry
			->getObserver()
			->trigger($this, 'beforeInitialize')
		;

		if ($event->isDefaultPrevented())
			return;

		$request = $this->makeRequest();

		//put components in registry
		$this->registry
			->setConnectionStorage($this->kit->makeConnectionStorage())
			->setRequest($request)
			->setResponse($this->makeResponse($request))
			->setFlashMessenger($this->kit->makeFlashMessenger())
		;

		$this->setupConnectionForTableModels();

		$this->initialized = true;

		$this->frontController = $this->makeFrontController();

		return $this;
	}

	/**
	 * Makes request.
	 *
	 * @return \KZ\controller\Request
	 */
	abstract public function makeRequest();

	/**
	 * Makes controllers factory.
	 *
	 * @return controller\Kit
	 */
	abstract public function makeControllerKit();

	/**
	 * @param controller\interfaces\Request $request
	 * @return \KZ\Controller\interfaces\Response
	 */
	public function makeResponse(controller\interfaces\Request $request)
	{
		return $this->kit->makeResponse($request);
	}

	/**
	 * @throws \RuntimeException
	 * @return controller\Front
	 */
	public function makeFrontController()
	{
		$this->checkIsInitialized();

		$controllerKit = $this->makeControllerKit();
		$frontController = $this->kit->makeFrontController($controllerKit, $this->getRegistry());

		$controllerKit->setFrontController($frontController);

		return $frontController;
	}

	/**
	 * @throws \RuntimeException
	 * @return \KZ\app\interfaces\Registry
	 */
	public function getRegistry()
	{
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

	/**
	 * @return controller\Front
	 * @throws \RuntimeException
	 */
	public function getFrontController()
	{
		$this->checkIsInitialized();

		return $this->frontController;

	}

	protected function checkIsInitialized()
	{
		if (!$this->initialized)
			throw new \RuntimeException('You must call initialize method before calling this one.');
	}

	protected function setupConnectionForTableModels()
	{
		$connection = $this->registry->getDb();
		if (isset($this->config['components']['db']['tableModelClass']) && $connection)
			call_user_func($this->config['components']['db']['tableModelClass'] . '::setDefaultConnection', $connection);
	}
} 
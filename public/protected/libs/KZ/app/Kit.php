<?php

namespace KZ\app;
use KZ\db;

/**
 * @see \KZ\app\interfaces\Kit
 *
 * Class Kit
 * @package KZ\app
 *
 */
class Kit implements interfaces\Kit
{
	/**
	 * @var array
	 */
	protected $config;

	/**
	 * Application config.
	 *
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * Create connectionStorage and fill it with PDO objects.
	 *
	 * @throws \RuntimeException
	 * @return \KZ\db\interfaces\ConnectionStorage
	 */
	public function makeConnectionStorage()
	{
		$instance = $this->makeInstance('\KZ\db\ConnectionStorage', 'connectionStorage');

		$instance->add($this->makePdo($this->config['db']), db\interfaces\ConnectionStorage::SQLITE, 'db', true);

		return $instance;
	}

	/**
	 * @return interfaces\Registry
	 * @throws \RuntimeException
	 */
	public function makeRegistry()
	{
		return $this->makeInstance('\KZ\app\Registry', 'registry');
	}

	/**
	 * Create controller chain.
	 *
	 * @throws \RuntimeException
	 * @return \KZ\controller\Chain
	 */
	public function makeControllerChain()
	{
		return $this->makeInstance('\KZ\controller\Chain', 'controllerChain');
	}

	/**
	 * @param \KZ\Controller\Kit $kit
	 * @param interfaces\Registry $registry
	 * @throws \RuntimeException
	 * @return \KZ\controller\Front
	 */
	public function makeFrontController(\KZ\Controller\Kit $kit, interfaces\Registry $registry)
	{
		return $this->makeInstance('\KZ\controller\Front', 'frontController', [
			$kit, $registry
		]);
	}

	/**
	 * Create Http request.
	 *
	 * @throws \RuntimeException
	 * @return \KZ\controller\request\Http
	 */
	public function makeHttpRequest()
	{
		return $this->makeInstance('\KZ\controller\request\Http', 'httpRequest');
	}

	/**
	 * Create controller Kit
	 *
	 * @param array $config
	 * @throws \RuntimeException
	 * @throws \OutOfBoundsException
	 * @return \KZ\controller\Kit
	 */
	public function makeControllerKit(array $config)
	{
		if (!array_key_exists('path', $config))
			throw new \OutOfBoundsException('Key "path" must be in config.');

		return $this->makeInstance('\KZ\controller\Kit', 'controllerKit', [
			$config['path'], $config
		]);
	}

	/**
	 * @param $templatesPath
	 * @param array $config
	 * @throws \RuntimeException
	 * @return \KZ\View
	 */
	public function makeView($templatesPath = null, array $config = [])
	{
		$viewConfig = isset($this->config['components']['view']) ? $this->config['components']['view'] : [];

		if (is_null($templatesPath) && isset($viewConfig['templatesPath']))
			$templatesPath = $viewConfig['templatesPath'];

		if (is_null($templatesPath))
			throw new \RuntimeException('templatesPath is null. You must pass it in method or specify it in a views config.');

		if (isset($viewConfig['config']) && is_array($viewConfig['config']))
			$config = array_replace($viewConfig['config'], $config);

		return $this->makeInstance('\KZ\View', 'view', [
			$templatesPath,
			$config
		]);
	}

	/**
	 * @param $className
	 * @param $configKey
	 * @param array $constructParams
	 * @throws \RuntimeException
	 * @return object
	 */
	protected function makeInstance($className, $configKey, array $constructParams = [])
	{
		$reflection = new \ReflectionClass($this->makeClassName($className, $configKey));
		$instance = $reflection->newInstanceArgs($constructParams);

		$this->validateInstance($instance, $className);

		return $instance;
	}

	protected function makeClassName($className, $configKey)
	{
		if (isset($this->config['components'][$configKey]['class']))
			return $this->config['components'][$configKey]['class'];

		return $className;
	}

	protected function validateInstance($instance, $implement)
	{
		if (!($instance instanceof $implement))
			throw new \RuntimeException('Instance must be interface of "' . $implement . '", given: "' . get_class($instance) . '".');
	}

	/**
	 * Create PDO object.
	 *
	 * @param array $config
	 * @return \PDO
	 */
	protected function makePdo(array $config)
	{
		$options = isset($config['options']) ? $config['options'] : [];

		$db = new \PDO($config['dsn'], $config['username'], $config['password'], $options);
		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		return $db;
	}
} 
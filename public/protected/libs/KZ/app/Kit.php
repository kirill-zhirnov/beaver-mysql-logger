<?php

namespace KZ\app;
use KZ\db;

/**
 * @see \KZ\app\interfaces\Kit
 *
 * Class Kit
 * @package KZ\app
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
		$className = '\KZ\db\ConnectionStorage';
		if (isset($this->config['components']['connectionStorage']['class']))
			$className = $this->config['components']['connectionStorage']['class'];

		$connectionStorage = new $className();

		if (!$connectionStorage instanceof db\interfaces\ConnectionStorage)
			throw new \RuntimeException('Connection storage must implement interface db\interfaces\ConnectionStorage.');

		$connectionStorage->add($this->makePdo($this->config['db']), db\interfaces\ConnectionStorage::SQLITE, 'db', true);

		return $connectionStorage;
	}

	/**
	 * @return interfaces\Registry
	 * @throws \RuntimeException
	 */
	public function makeRegistry()
	{
		$className = '\KZ\app\Registry';
		if (isset($this->config['components']['registry']['class']))
			$className = $this->config['components']['registry']['class'];

		$registry = new $className();

		if (!$registry instanceof interfaces\Registry)
			throw new \RuntimeException('Registry must implement interface interfaces\Registry.');

		return $registry;
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
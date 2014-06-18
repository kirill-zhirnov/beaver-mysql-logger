<?php

namespace KZ\app;
use KZ\db;

/**
 * We need special registry, which store and returns necessary components for application.
 *
 * Class Registry
 * @package KZ\app
 *
 * Following properties are available via magic methods (get/set):
 * @property \PDO $db
 * @property \PDO $mysql
 * @property \KZ\db\interfaces\ConnectionStorage $connectionStorage
 * @property \KZ\app\interfaces\Kit $kit
 */
class Registry extends \KZ\Registry implements interfaces\Registry
{
	/**
	 * @return \PDO
	 */
	public function getDb()
	{
		return $this->getConnectionStorage()->getDefault();
	}

	/**
	 * @throws \UnderflowException
	 * @return \KZ\db\interfaces\ConnectionStorage
	 */
	public function getConnectionStorage()
	{
		$this->checkKey('connectionStorage');

		return $this->data['connectionStorage'];
	}

	/**
	 * @param db\interfaces\ConnectionStorage $connectionStorage
	 * @return $this
	 */
	public function setConnectionStorage(\KZ\db\interfaces\ConnectionStorage $connectionStorage)
	{
		$this->data['connectionStorage'] = $connectionStorage;

		return $this;
	}

	/**
	 * @throws \UnderflowException
	 * @return \KZ\app\interfaces\Kit
	 */
	public function getKit()
	{
		$this->checkKey('kit');

		return $this->data['kit'];
	}

	/**
	 * @param \KZ\app\interfaces\Kit $kit
	 * @return $this
	 */
	public function setKit(\KZ\app\interfaces\Kit $kit)
	{
		$this->data['kit'] = $kit;

		return $this;
	}

	/**
	 * @return array
	 * @throws \UnderflowException
	 */
	public function getConfig()
	{
		$this->checkKey('config');

		return $this->data['config'];
	}

	/**
	 * @param array $config
	 * @return $this
	 */
	public function setConfig(array $config)
	{
		$this->data['config'] = $config;

		return $this;
	}

	/**
	 * @throws \UnderflowException
	 * @return \KZ\controller\Request
	 */
	public function getRequest()
	{
		$this->checkKey('request');

		return $this->data['request'];
	}

	/**
	 * @param \KZ\controller\Request $request
	 * @return $this
	 */
	public function setRequest(\KZ\controller\Request $request)
	{
		$this->data['request'] = $request;

		return $this;
	}

	/**
	 * @return \KZ\event\Observer
	 */
	public function getObserver()
	{
		$this->checkKey('observer');

		return $this->data['observer'];
	}

	/**
	 * @param \KZ\event\Observer $observer
	 * @return $this
	 */
	public function setObserver(\KZ\event\Observer $observer)
	{
		$this->data['observer'] = $observer;

		return $this;
	}

	protected function checkKey($key)
	{
		if (!isset($this->data[$key]))
			throw new \UnderflowException('You must setup "' . $key . '" before calling this method!');
	}
} 
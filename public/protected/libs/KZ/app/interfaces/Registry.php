<?php

namespace KZ\app\interfaces;

/**
 * We need special registry, which store and returns necessary components for application.
 *
 * Interface Registry
 * @package KZ\app\interfaces
 */
interface Registry extends \ArrayAccess, \Iterator
{
	/**
	 * @return \PDO
	 */
	public function getDb();

	/**
	 * @return \KZ\db\interfaces\ConnectionStorage
	 */
	public function getConnectionStorage();

	/**
	 * @param \KZ\db\interfaces\ConnectionStorage $connectionStorage
	 * @return $this
	 */
	public function setConnectionStorage(\KZ\db\interfaces\ConnectionStorage $connectionStorage);

	/**
	 * @return \KZ\app\interfaces\Kit
	 */
	public function getKit();

	/**
	 * @param Kit $kit
	 * @return $this
	 */
	public function setKit(Kit $kit);

	/**
	 * @return array
	 */
	public function getConfig();

	/**
	 * @param array $config
	 * @return $this
	 */
	public function setConfig(array $config);

	/**
	 * @return \KZ\controller\Request
	 */
	public function getRequest();

	/**
	 * @param \KZ\controller\Request $request
	 * @return $this
	 */
	public function setRequest(\KZ\controller\Request $request);

	/**
	 * @return \KZ\event\Observer
	 */
	public function getObserver();

	/**
	 * @param \KZ\event\Observer $observer
	 * @return $this
	 */
	public function setObserver(\KZ\event\Observer $observer);

	/**
	 * @return \KZ\flashMessenger\interfaces\FlashMessenger
	 */
	public function getFlashMessenger();

	/**
	 * @param \KZ\flashMessenger\interfaces\FlashMessenger $messenger
	 * @return $this
	 */
	public function setFlashMessenger(\KZ\flashMessenger\interfaces\FlashMessenger $messenger);
}
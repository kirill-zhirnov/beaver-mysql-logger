<?php

namespace KZ\view\interfaces;
use KZ\app\interfaces as appInterfaces;

/**
 * This is an Abstract Factory for view helpers.
 *
 * Interface HelperKit
 * @package KZ\view\interfaces
 */
interface HelperKit
{
	/**
	 * @param array $config
	 */
	public function __construct(array $config = []);

	/**
	 * @param array $config
	 * @return $this
	 */
	public function setConfig(array $config);

	/**
	 * @return array
	 */
	public function getConfig();

	/**
	 * Check if helper exists or not.
	 *
	 * @param $name
	 * @return boolean
	 */
	public function helperExists($name);

	/**
	 * Returns helper instance.
	 *
	 * @param $name
	 * @return Helper
	 */
	public function getHelper($name);

	/**
	 * @param appInterfaces\Registry $registry
	 * @return $this
	 */
	public function setRegistry(appInterfaces\Registry $registry);

	/**
	 * @return appInterfaces\Registry
	 */
	public function getRegistry();
} 
<?php

namespace KZ\event\interfaces;

/**
 * Interface Event
 * @package KZ\event\interfaces
 */
interface Event
{
	/**
	 * @param $sender - Object which triggered event.
	 * @param array $params
	 */
	public function __construct($sender, array $params = []);

	/**
	 * @return boolean
	 */
	public function isDefaultPrevented();

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function getParam($key);

	/**
	 * @param string $key
	 * @return boolean
	 */
	public function hasParam($key);

	/**
	 * @param array $params
	 * @return $this
	 */
	public function setParams(array $params);

	/**
	 * @return array
	 */
	public function getParams();

	/**
	 * @return Object
	 */
	public function getSender();

	/**
	 * @return $this
	 */
	public function preventDefault();
} 
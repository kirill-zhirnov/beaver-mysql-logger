<?php

namespace KZ\event;

class Event implements interfaces\Event
{
	/**
	 * Is default prevented?
	 *
	 * @var bool
	 */
	protected $defaultPrevented = false;

	/**
	 * @var object - Sender of the event.
	 */
	protected $sender;

	/**
	 * @var array
	 */
	protected $params = [];

	/**
	 * @param $sender
	 * @param array $params
	 */
	public function __construct($sender, array $params = [])
	{
		$this->sender = $sender;
		$this->setParams($params);
	}

	/**
	 * @return bool
	 */
	public function isDefaultPrevented()
	{
		return $this->defaultPrevented;
	}

	/**
	 * @param string $key
	 * @return mixed
	 * @throws \OutOfBoundsException
	 */
	public function getParam($key)
	{
		if (!$this->hasParam($key))
			throw new \OutOfBoundsException('Key "' . $key . '" not found!');

		return $this->params[$key];
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasParam($key)
	{
		return isset($this->params[$key]);
	}

	/**
	 * @param array $params
	 * @return $this
	 */
	public function setParams(array $params)
	{
		foreach ($params as $key => $val)
			$this->params[$key] = $val;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * @return Object
	 */
	public function getSender()
	{
		return $this->sender;
	}

	/**
	 * @return $this
	 */
	public function preventDefault()
	{
		$this->defaultPrevented = true;

		return $this;
	}
}
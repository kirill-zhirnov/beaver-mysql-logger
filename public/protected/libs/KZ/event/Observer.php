<?php

namespace KZ\event;

class Observer implements interfaces\Observer
{
	/**
	 * Array with listeners.
	 *
	 * @var array
	 */
	protected $listeners = [];

	/**
	 * To understand structure, see:
	 * @see \KZ\event\interfaces\Observer::bindEvents()
	 * @param array $events
	 */
	public function __construct(array $events = [])
	{
		if ($events)
			$this->bindEvents($events);
	}

	/**
	 * @see \KZ\event\interfaces\Observer::bindEvents()
	 * @param array $events.
	 * @return $this
	 * @throws \OutOfBoundsException
	 */
	public function bindEvents(array $events)
	{
		foreach ($events as $event) {
			if (!isset($event[0], $event[1], $event[2]))
				throw new \OutOfBoundsException('Incorrect event row.');

			$this->bind($event[0], $event[1], $event[2]);
		}

		return $this;
	}

	/**
	 * @see \KZ\event\interfaces\Observer::bind()
	 * @param $class
	 * @param $name
	 * @param callable $callback
	 * @return int
	 */
	public function bind($class, $name, callable $callback)
	{
		$class = $this->prepareClass($class);

		if (!isset($this->listeners[$class]))
			$this->listeners[$class] = [];

		if (!isset($this->listeners[$class][$name]))
			$this->listeners[$class][$name] = [];

		return array_push($this->listeners[$class][$name], $callback) - 1;
	}

	/**
	 * @see \KZ\event\interfaces\Observer
	 * @param object $sender
	 * @param $name
	 * @param array $params
	 * @throws \InvalidArgumentException
	 * @return interfaces\Event
	 */
	public function trigger($sender, $name, array $params = [])
	{
		if (!is_object($sender))
			throw new \InvalidArgumentException('Sender must be an object!');

		$class = $this->prepareClass($sender);

		$event = new Event($sender, $params);

		if (isset($this->listeners[$class][$name]))
			foreach ($this->listeners[$class][$name] as $callback)
				call_user_func($callback, $event);

		return $event;
	}

	/**
	 * Unbind event|s.
	 *
	 * @param null $class - if $class passed - only event related to this class will be removed.
	 * @param null $name - if $name passed, $class has to be passed too. Only $class => $name events will be removed.
	 * @param null $key
	 * @return boolean - if listeners exist - it will unset and return true, if not - false.
	 */
	public function unbind($class = null, $name = null, $key = null)
	{
		if (isset($class, $name, $key)) {
			if (isset($this->listeners[$class][$name][$key])) {
				unset($this->listeners[$class][$name][$key]);
				return true;
			}

			return false;
		}

		if (isset($class, $name)) {
			if (isset($this->listeners[$class][$name])) {
				unset($this->listeners[$class][$name]);
				return true;
			}

			return false;
		}

		if (isset($class)) {
			if (isset($this->listeners[$class])) {
				unset($this->listeners[$class]);
				return true;
			}

			return false;
		}

		$this->listeners = [];

		return true;
	}

	/**
	 * Get listeners.
	 *
	 * @param null $class
	 * @param null $name
	 * @param null $key
	 * @return array|callable|boolean
	 */
	public function getListeners($class = null, $name = null, $key = null)
	{
		if (isset($class, $name, $key)) {
			if (isset($this->listeners[$class][$name][$key]))
				return $this->listeners[$class][$name][$key];

			return false;
		}

		if (isset($class, $name)) {
			if (isset($this->listeners[$class][$name]))
				return $this->listeners[$class][$name];

			return false;
		}

		if (isset($class)) {
			if (isset($this->listeners[$class]))
				return $this->listeners[$class];

			return false;
		}

		return $this->listeners;
	}

	public function prepareClass($class)
	{
		if (is_object($class))
			$class = get_class($class);

		if (!class_exists($class, false))
			return $class;

		while ($tmpClass = get_parent_class($class))
			$class = $tmpClass;

		return $class;
	}
} 
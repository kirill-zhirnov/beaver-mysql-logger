<?php

namespace KZ\events\interfaces;

/**
 * Interface Events
 * @package KZ\events\interfaces
 */
interface Events
{
	/**
	 * @param array $events - If you want to bind many events in construct - pass it here.
	 */
	public function __construct(array $events = []);

	/**
	 * @param array $events Same with __construct $events.
	 * @return $this
	 */
	public function bindEvents(array $events);

	/**
	 * Bind single event.
	 *
	 * @param $class
	 * @param $name
	 * @param callable $callback
	 * @return $this
	 */
	public function bind($class, $name, callable $callback);

	/**
	 * Trigger event.
	 *
	 * @param $class
	 * @param $name
	 * @param array $params
	 * @return Event
	 */
	public function trigger($class, $name, array $params = []);

	/**
	 * Unbind event|s.
	 *
	 * @param null $class - if $class passed - only event related to this class will be removed.
	 * @param null $name - if $name passed, $class has to be passed too. Only $class => $name events will be removed.
	 * @param null $key
	 * @return $this
	 */
	public function unbind($class = null, $name = null, $key = null);
} 
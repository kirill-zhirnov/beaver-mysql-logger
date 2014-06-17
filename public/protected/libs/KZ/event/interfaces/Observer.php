<?php

namespace KZ\event\interfaces;

/**
 * Interface Observer
 * @package KZ\event\interfaces
 */
interface Observer
{
	/**
	 * @param array $events - If you want to bind many events in construct - pass it here.
	 */
	public function __construct(array $events = []);

	/**
	 * @param array $events Same with __construct $events.
	 * Array structure must be:
	 * <code>
	 * <?php
	 * $events = array(array('ClassName', 'name', function() {}));
	 * ?>
	 * </code>
	 * @return $this
	 */
	public function bindEvents(array $events);

	/**
	 * Bind single event.
	 *
	 * @param $class
	 * @param $name
	 * @param callable $callback
	 * @return int - Position in listeners array.
	 */
	public function bind($class, $name, callable $callback);

	/**
	 * Trigger event.
	 *
	 * @param $class
	 * @param $name
	 * @param $sender
	 * @param array $params
	 * @return Event
	 */
	public function trigger($class, $name, $sender, array $params = []);

	/**
	 * Unbind event|s.
	 *
	 * @param null $class - if $class passed - only event related to this class will be removed.
	 * @param null $name - if $name passed, $class has to be passed too. Only $class => $name events will be removed.
	 * @param null $key
	 * @return boolean - if listeners exist - it will unset and return true, if not - false.
	 */
	public function unbind($class = null, $name = null, $key = null);

	/**
	 * Get listeners.
	 *
	 * @param null $class
	 * @param null $name
	 * @param null $key
	 * @return array|callable|boolean
	 */
	public function getListeners($class = null, $name = null, $key = null);
} 
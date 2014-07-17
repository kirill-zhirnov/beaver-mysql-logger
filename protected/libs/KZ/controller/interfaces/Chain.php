<?php

namespace KZ\controller\interfaces;

/**
 * Chain with Controller instances.
 *
 * Interface Chain
 * @package KZ\controller\interfaces
 */
interface Chain extends \Iterator
{
	/**
	 * @param array $controllers
	 */
	public function __construct(array $controllers);

	/**
	 * Add controller to chain.
	 *
	 * @param \KZ\Controller $controller
	 * @param $action
	 * @return $this
	 */
	public function push(\KZ\Controller $controller, $action);

	/**
	 * Add controller to chain after current position in iterator.
	 *
	 * @param \KZ\Controller $controller
	 * @param $action
	 * @return $this
	 */
	public function pushAfterCurrent(\KZ\Controller $controller, $action);

	/**
	 * Add controller to chain at specific position in iterator.
	 *
	 * @param $position
	 * @param \KZ\Controller $controller
	 * @param $action
	 * @return $this
	 */
	public function pushAtPosition($position, \KZ\Controller $controller, $action);
} 
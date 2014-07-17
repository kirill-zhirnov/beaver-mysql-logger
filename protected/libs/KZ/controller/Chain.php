<?php

namespace KZ\controller;

/**
 * Class Chain
 * @package KZ\controller
 */
class Chain implements interfaces\Chain
{
	/**
	 * Controllers.
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Current position in iterator
	 *
	 * @var int
	 */
	protected $position = 0;

	/**
	 * @param array $controllers
	 */
	public function __construct(array $controllers = [])
	{
		foreach ($controllers as $item)
			$this->push($item['instance'], $item['action']);
	}

	/**
	 * @see \KZ\Controller\interfaces\Chain::push()
	 * @param \KZ\Controller $controller
	 * @param $action
	 * @return $this
	 */
	public function push(\KZ\Controller $controller, $action)
	{
		$this->data[] = $this->prepareDataItem($controller, $action);

		return $this;
	}

	/**
	 * @see \KZ\Controller\interfaces\Chain::pushAfterCurrent()
	 * @param \KZ\Controller $controller
	 * @param $action
	 * @return $this
	 */
	public function pushAfterCurrent(\KZ\Controller $controller, $action)
	{
		return $this->pushAtPosition($this->position + 1, $controller, $action);
	}

	/**
	 * @see \KZ\Controller\interfaces\Chain::pushAtPosition()
	 * @param $position
	 * @param \KZ\Controller $controller
	 * @param $action
	 * @return $this
	 */
	public function pushAtPosition($position, \KZ\Controller $controller, $action)
	{
		$this->data = array_merge(
			array_slice($this->data, 0, $position),
			[$this->prepareDataItem($controller, $action)],
			array_slice($this->data, $position)
		);

		return $this;
	}

	/**
	 * Prepare item which will be put in chain as element of the chain.
	 *
	 * @param \KZ\Controller $controller
	 * @param $action
	 * @return array
	 */
	public function prepareDataItem(\KZ\Controller $controller, $action)
	{
		return [
			'instance' => $controller,
			'action' => $action
		];
	}

	public function current()
	{
		return $this->data[$this->position];
	}

	public function next()
	{
		$this->position++;
	}

	public function key()
	{
		return $this->position;
	}

	public function valid()
	{
		return isset($this->data[$this->position]);
	}

	public function rewind()
	{
		$this->position = 0;
	}
} 
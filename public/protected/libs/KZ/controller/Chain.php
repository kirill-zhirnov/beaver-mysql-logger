<?php

namespace KZ\controller;

class Chain implements interfaces\Chain
{
	protected $data;

	protected $position = 0;

	public function __construct(array $controllers)
	{
		foreach ($controllers as $item)
			$this->push($item['instance'], $item['action']);
	}

	public function push(\KZ\Controller $controller, $action)
	{
		$this->data[] = $this->prepareDataItem($controller, $action);
	}

	public function pushAfterCurrent(\KZ\Controller $controller, $action)
	{
		return $this->pushAfterPosition($this->position, $controller, $actio);
	}

	public function pushAfterPosition($position, \KZ\Controller $controller, $action)
	{
		$this->data = array_merge(
			array_slice($this->data, 0, $position),
			$this->prepareDataItem($controller, $action),
			array_slice($this->data, $position)
		);

		return $this;
	}

	public function prepareDataItem(\KZ\Controller $controller, $action)
	{
		return [
			'instance' => $controller,
			'action' => $action
		];
	}

	public function current()
	{
		$this->data[$this->position];
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
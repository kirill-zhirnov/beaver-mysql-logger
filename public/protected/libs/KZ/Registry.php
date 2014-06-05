<?php

namespace KZ;

class Registry implements \ArrayAccess, \Iterator
{
	protected $data = [];

	protected $position = 0;

	protected $keys;

	public function __construct(array $data = [])
	{
		foreach ($data as $key => $val)
			$this->offsetSet($key, $val);
	}

	public function __set($name, $value)
	{
		return $this->offsetSet($name, $value);
	}

	public function __get($name)
	{
		return $this->offsetGet($name);
	}

	public function rewind()
	{
		$this->position = 0;
	}

	public function current()
	{
		return $this->data[$this->keys[$this->position]];
	}

	public function next()
	{
		$this->position++;
	}

	public function key()
	{
		return $this->keys[$this->position];
	}

	public function valid()
	{
		$this->initIteratorKeys();

		return isset($this->keys[$this->position]);
	}

	public function offsetExists($offset)
	{
		return isset($this->data[$offset]);
	}

	public function offsetGet($offset)
	{
		$methodName = 'get' . $offset;
		if (method_exists($this, $methodName))
			return $this->{$methodName}();

		return isset($this->data[$offset]) ? $this->data[$offset] : null;
	}

	/**
	 * @param string $offset - Allow only names which are correct PHP names.
	 * @param mixed $value
	 * @return $this
	 * @throws \OutOfBoundsException
	 */
	public function offsetSet($offset, $value)
	{
		if (!preg_match('/^(_|[a-z])\w*/i', $offset))
			throw new \OutOfBoundsException('Key should be right PHP variable name ("' . $offset . '")');

		$methodName = 'set' . $offset;
		if (method_exists($this, $methodName))
			return $this->{$methodName}($value);

		$this->data[$offset] = $value;

		return $this;
	}

	public function offsetUnset($offset)
	{
		unset($this->data[$offset]);

		return $this;
	}

	protected function initIteratorKeys()
	{
		$this->keys = array_keys($this->data);

		return $this;
	}
} 
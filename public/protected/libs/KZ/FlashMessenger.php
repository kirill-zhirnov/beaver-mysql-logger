<?php

namespace KZ;

class FlashMessenger implements  flashMessenger\interfaces\FlashMessenger
{
	/**
	 * Messages storage.
	 *
	 * @var array
	 */
	protected $messages;

	/**
	 * @var int
	 */
	protected $iteratorPosition = 0;

	/**
	 * Key in session.
	 *
	 * @var string
	 */
	protected $sessionPrefix = 'flashMessenger';

	public function __construct(array $config = [])
	{
		$this->setConfig($config);
		$this->initMessages();
	}

	/**
	 * Add message,
	 *
	 * @param $text
	 * @param string $type - Message type.
	 * @return int - Return position for added item.
	 */
	public function add($text, $type = self::SUCCESS)
	{
		$size = array_push($this->messages, [
			'text' => $text,
			'type' => $type,
			'liveCounter' => 0
		]);

		return $size - 1;
	}

	/**
	 * Extend messages to next time.
	 *
	 * @return $this
	 */
	public function extend()
	{
		foreach ($this->messages as $key => $val)
			if ($this->messages[$key]['liveCounter'] > 0)
				$this->messages[$key]['liveCounter']--;

		return $this;
	}

	/**
	 * Delete message.
	 *
	 * @param $position
	 * @return $this
	 */
	public function delete($position)
	{
		if (isset($this->messages[$position])
			unset($this->messages[$position]);

		return $this;
	}

	/**
	 * Get all messages by type.
	 *
	 * @param $type
	 * @return array
	 */
	public function getAllByType($type)
	{
		$out = [];

		foreach ($this->messages as $pos => $val) {
			if ($val['type'] != $type)
				continue;

			$out[] = $val['text'];
		}

		return $out;
	}


	/**
	 * @param string $sessionPrefix
	 * @throws \InvalidArgumentException
	 * @return $this
	 */
	public function setSessionPrefix($sessionPrefix)
	{
		if (trim($sessionPrefix) == '')
			throw new \InvalidArgumentException('Session prefix cannot be empty!');

		$changed = ($sessionPrefix != $this->sessionPrefix) ? true : false;
		$this->sessionPrefix = $sessionPrefix;

		if (isset($this->messages) && $changed)
			$this->initMessages();

		return $this;
	}

	/**
	 * @return string
	 */
	public function getSessionPrefix()
	{
		return $this->sessionPrefix;
	}

	/**
	 * Set config.
	 *
	 * @param array $config
	 * @return $this
	 */
	public function setConfig(array $config)
	{
		$allowedOptions = [
			'sessionPrefix'
		];

		foreach ($allowedOptions as $key) {
			if (!isset($config[$key]))
				continue;

			$method = 'set' . $key;
			if (method_exists($this, $method))
				$this->{$method}($config[$key]);
			else
				$this->{$key} = $config[$key];
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function current()
	{
		return $this->messages[$this->iteratorPosition]['text'];
	}

	/**
	 * Key returns type.
	 *
	 * @return string
	 */
	public function key()
	{
		return $this->messages[$this->iteratorPosition]['type'];
	}

	public function rewind()
	{
		$this->iteratorPosition = 0;
	}

	public function next()
	{
		$this->iteratorPosition++;
	}

	public function valid()
	{
		return isset($this->messages[$this->iteratorPosition]);
	}

	protected function initMessages()
	{
		if (!isset($_SESSION[$this->sessionPrefix]))
			$_SESSION[$this->sessionPrefix] = [];

		$this->messages = &$_SESSION[$this->sessionPrefix];

		foreach ($this->messages as $key => &$val) {
			$val['liveCounter']++;

			if ($val['liveCounter'] == 2)
				unset($this->messages[$key]['liveCounter']);
		}
	}
} 
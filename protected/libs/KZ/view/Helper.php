<?php

namespace KZ\view;

use KZ\app\interfaces as appInterfaces;

class Helper implements interfaces\Helper
{
	/**
	 * @var appInterfaces\Registry
	 */
	protected $registry;

	/**
	 * @param appInterfaces\Registry $registry
	 * @return $this
	 */
	public function setRegistry(appInterfaces\Registry $registry)
	{
		$this->registry = $registry;

		return $this;
	}

	/**
	 * @throws \RuntimeException
	 * @return appInterfaces\Registry
	 */
	public function getRegistry()
	{
		if (!$this->registry)
			throw new \RuntimeException('You must set registry before calling this method.');

		return $this->registry;
	}
} 
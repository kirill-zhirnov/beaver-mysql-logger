<?php

namespace KZ\view\interfaces;
use KZ\app\interfaces as appInterfaces;

interface Helper
{
	/**
	 * @param appInterfaces\Registry $registry
	 * @return $this
	 */
	public function setRegistry(appInterfaces\Registry $registry);

	/**
	 * @return appInterfaces\Registry
	 */
	public function getRegistry();
}
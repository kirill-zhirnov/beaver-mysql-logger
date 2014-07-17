<?php

namespace KZ\app\Facade;
use KZ\app;

class Http extends app\Facade
{
	/**
	 * Makes request.
	 *
	 * @return \KZ\controller\Request
	 */
	public function makeRequest()
	{
		return $this->kit->makeHttpRequest();
	}

	/**
	 * Makes controllers factory.
	 *
	 * @throws \OutOfBoundsException
	 * @return \KZ\controller\Kit
	 */
	public function makeControllerKit()
	{
		if (!array_key_exists('httpControllerKit', $this->config['components']))
			throw new \OutOfBoundsException('Key "httpControllerKit" must be in config!');

		return $this->kit->makeControllerKit($this->config['components']['httpControllerKit']);
	}
} 
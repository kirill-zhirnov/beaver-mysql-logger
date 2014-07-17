<?php

namespace KZ\view\helpers;
use KZ\view;

class Link extends view\Helper
{
	/**
	 * @param $route
	 * @param array $params
	 * @return \KZ\Link
	 */
	public function get($route, array $params = [])
	{
		return new \KZ\Link($route, $params);
	}
} 
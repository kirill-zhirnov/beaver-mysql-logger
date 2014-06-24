<?php

namespace KZ\view\helpers;
use KZ\view\interfaces;

class Link implements interfaces\Helper
{
	public function get($route, array $params = [])
	{
		return new \KZ\Link($route, $params);
	}
} 
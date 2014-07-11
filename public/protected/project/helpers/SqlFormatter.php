<?php

namespace helpers;
use KZ\view;

class SqlFormatter extends view\Helper
{
	public function format($string, $highlight = true)
	{
		return \SqlFormatter::format($string, $highlight);
	}
}
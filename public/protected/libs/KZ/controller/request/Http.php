<?php

namespace KZ\controller\request;
use KZ\controller;

class Http extends controller\Request
{
	public function __construct()
	{
		$route = (isset($_GET['r'])) ? $_GET['r'] : '';

		parent::__construct($route);
	}

	public function getParam($key, $default = null)
	{
		if (array_key_exists($key, $this->params))
			return $this->params[$key];
		elseif (isset($_POST[$key]))
			return $_POST[$key];
		else
			return $_GET[$key];

		return $default;
	}

	public function getScriptName()
	{
		return $_SERVER['SCRIPT_NAME'];
	}

	public function isAjaxRequest()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
	}
} 
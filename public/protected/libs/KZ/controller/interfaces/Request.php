<?php

namespace KZ\controller\interfaces;
//todo: написать комментарии
interface Request
{
	public function getController();

	public function getAction();

	public function getParam($key, $default = null);

	public function getParams();

	public function getScriptName();

	public function setParams(array $params);
} 
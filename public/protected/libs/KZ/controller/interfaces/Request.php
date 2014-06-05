<?php

namespace KZ\controller\interfaces;

/**
 * The main task: parse request and return controller path, controller and action.
 *
 * Interface Request
 * @package KZ\controller\interfaces
 */
interface Request
{
	/**
	 * Returns sub-path for controller. If route is "folder/sub/controller/action", this method will return "folder/sub".
	 *
	 * @return string
	 */
	public function getControllerPath();

	/**
	 * Returns controller name. For route "path/controller/action" this method will return "controller".
	 *
	 * @return string
	 */
	public function getController();

	/**
	 * Returns action name by route.
	 *
	 * @return string
	 */
	public function getAction();

	public function getParam($key, $default = null);

	public function getParams();

	public function setParams(array $params);

	/**
	 * Return $_SERVER['SCRIPT_NAME'] for HTTP requests.
	 *
	 * @return string
	 */
	public function getScriptName();
}
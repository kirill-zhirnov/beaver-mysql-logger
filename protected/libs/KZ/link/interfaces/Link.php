<?php

namespace KZ\link\interfaces;

/**
 * Makes urls.
 *
 * Interface Link
 * @package KZ\link\interfaces
 */
interface Link
{
	/**
	 * @param string $route for example: index/index or path/to/controller/action
	 * @param array $params Get params
	 */
	public function __construct($route, array $params = []);

	/**
	 * @see self::getLink()
	 * @return string
	 */
	public function __toString();

	/**
	 * Creates url by given params and returns it.
	 * @return string
	 */
	public function getLink();

	/**
	 * Set GET params.
	 *
	 * @param array $params
	 * @return $this
	 */
	public function setParams(array $params);

	/**
	 * Remove all params
	 *
	 * @return $this
	 */
	public function clearParams();

	/**
	 * @return array - params
	 */
	public function getParams();

	/**
	 * @param string $route - set route
	 * @return $this
	 */
	public function setRoute($route);

	/**
	 * @return string
	 */
	public function getRoute();

	/**
	 * @param string $scriptName
	 * @return $this
	 */
	public function setScriptName($scriptName);

	/**
	 * @return string
	 */
	public function getScriptName();

	/**
	 * Script name will be taken from request.
	 *
	 * @param \KZ\controller\interfaces\Request $request
	 * @return $this
	 */
	public function setRequest(\KZ\controller\interfaces\Request $request);

	/**
	 * @param \KZ\model\interfaces\Model $model
	 * @param array $attributes
	 * @param bool $onlyNotEmpty
	 * @return $this
	 */
	public function appendModelAttrs(\KZ\model\interfaces\Model $model, array $attributes = null, $onlyNotEmpty = true);
} 
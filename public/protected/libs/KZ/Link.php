<?php

namespace KZ;

class Link implements link\interfaces\Link
{
	const ROUTE_PARAM = 'r';

	/**
	 * @var array
	 */
	protected $params = [];

	/**
	 * @var string
	 */
	protected $route;

	/**
	 * @var string
	 */
	protected $scriptName;

	/**
	 * @param string $route for example: index/index or path/to/controller/action
	 * @param array $params Get params
	 */
	public function __construct($route, array $params = [])
	{
		$this->setRoute($route);

		if ($params)
			$this->setParams($params);
	}

	/**
	 * @see self::getLink()
	 * @return string
	 */
	public function __toString()
	{
		return $this->getLink();
	}

	/**
	 * Creates url by given params and returns it.
	 * @return string
	 */
	public function getLink()
	{
		return $this->getScriptName() . '?' . http_build_query(array_merge(
			[self::ROUTE_PARAM => $this->getRoute()],
			$this->getParams()
		));
	}

	/**
	 * Set GET params.
	 *
	 * @param array $params
	 * @return $this
	 */
	public function setParams(array $params)
	{
		if (isset($params[self::ROUTE_PARAM]))
			unset($params[self::ROUTE_PARAM]);

		$this->params = array_replace($this->params, $params);

		return $this;
	}

	/**
	 * Remove all params
	 *
	 * @return $this
	 */
	public function clearParams()
	{
		$this->params = [];

		return $this;
	}

	/**
	 * @return array - params
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * @param string $route - set route
	 * @return $this
	 */
	public function setRoute($route)
	{
		$this->route = $route;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getRoute()
	{
		return $this->route;
	}

	/**
	 * @param string $scriptName
	 * @return $this
	 */
	public function setScriptName($scriptName)
	{
		$this->scriptName = $scriptName;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getScriptName()
	{
		return $this->scriptName;
	}

	/**
	 * Script name will be taken from request.
	 *
	 * @param \KZ\controller\interfaces\Request $request
	 * @return $this
	 */
	public function setRequest(\KZ\controller\interfaces\Request $request)
	{
		$this->setScriptName($request->getScriptName());

		return $this;
	}

	/**
	 * @param \KZ\model\interfaces\Model $model
	 * @param array $attributes
	 * @param bool $onlyNotEmpty
	 * @return $this
	 */
	public function appendModelAttrs(\KZ\model\interfaces\Model $model, array $attributes = null, $onlyNotEmpty = true)
	{
		$prefix = $this->getModelPrefix($model);

		$params = [];
		foreach ($model->getAttributes() as $attr => $value) {
			if (is_array($attributes) && !in_array($attr, $attributes))
				continue;

			if ($onlyNotEmpty && $value == '')
				continue;

			$params[$prefix . '[' . $attr . ']'] = $value;
		}

		$this->setParams($params);

		return $this;
	}

	/**
	 *
	 * Returns prefix for model
	 *
	 * @param \KZ\model\interfaces\Model $model
	 * @return string
	 */
	public function getModelPrefix(\KZ\model\interfaces\Model $model)
	{
		return $model->getLinkPrefix();
	}
} 
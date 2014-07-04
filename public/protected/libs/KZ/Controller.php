<?php

namespace KZ;

/**
 * Class Controller
 * @package KZ
 *
 * Magic methods:
 * @method void render() render(\string $localPath, array $data = [])
 * @method void redirect() redirect(\string $url)
 * @method controller\interfaces\Response setJson() setJson(array $json)
 * @method void json() json(array $json = [])
 *
 * Magic property:
 * @property-read flashMessenger\interfaces\FlashMessenger $flashMessenger
 */
abstract class Controller
{
	/**
	 * @var controller\Front
	 */
	protected $frontController;

	/**
	 * @var view\interfaces\View
	 */
	protected $view;

	/**
	 * @var string
	 */
	protected $layoutLocalPath = 'layout';

	/**
	 * @var app\Registry
	 */
	protected $registry;

	/**
	 * @var controller\interfaces\Response
	 */
	protected $response;

	public function __construct(controller\Front $frontController)
	{
		$this->frontController = $frontController;

		$this->init();
	}

	public function __call($name, array $args)
	{
		if (in_array($name, ['render', 'redirect', 'setJson', 'json']))
			return call_user_func_array([$this->response, $name], $args);

		throw new \BadMethodCallException('Method "' . $name . '" does not exist!');
	}

	public function __get($name)
	{
		switch ($name) {
			case 'flashMessenger':
				return $this->registry->getFlashMessenger();
			default:
				throw new \DomainException('Unknown property "' . $name . '"');
		}
	}

	public function posted()
	{
		$models = [];
		foreach (func_get_args() as $model) {
			if (is_array($model))
				$models = array_merge($models, $model);
			else
				$models[] = $model;
		}

		$out = false;
		foreach ($models as $model) {
			if (!$model instanceof model\interfaces\Model)
				throw new \UnexpectedValueException('Argument must be instance of KZ\model\interfaces\Model or an array!');

			/** @var string $prefix */
			$prefix = $this->view->helper('html')->getModelPrefix($model);

			if (isset($_POST[$prefix])) {
				$model->setAttributes($_POST[$prefix]);
				$out = true;
			}
		}

		return $out;
	}

	/**
	 * @param $route
	 * @param array $params
	 * @return link\interfaces\Link
	 */
	public function makeLink($route, array $params = [])
	{
		return $this->frontController->makeLink($route, $params);
	}

	/**
	 * @return view\interfaces\View
	 */
	public function getView()
	{
		return $this->view;
	}

	/**
	 * @param view\interfaces\View $view
	 * @return $this
	 */
	public function setView(view\interfaces\View $view)
	{
		$this->view = $view;

		return $this;
	}

	protected function init()
	{
		$this->registry = $this->frontController->getRegistry();

		$this->response = $this->registry->getResponse();
		$this->response->setController($this);

		$this->initializeView();
	}

	protected function initializeView()
	{
		$this->view = $this->registry->getKit()->makeView();

		/** Set registry to HelperKit, since it is singleton */
		if ($this->registry)
			$this->view
				->getHelperKit()
				->setRegistry($this->registry)
			;

		if ($this->layoutLocalPath) {
			$layout = $this->registry->getKit()->makeView(null, [
				'localPath' => $this->layoutLocalPath
			]);
			$this->view->setLayout($layout);
		}
	}
}
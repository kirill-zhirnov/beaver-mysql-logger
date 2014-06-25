<?php

namespace KZ;
use KZ\model\interfaces\Model;

/**
 * Class Controller
 * @package KZ
 */
abstract class Controller
{
	/**
	 * @var controller\Front
	 */
	protected $frontController;

	/**
	 * @var View
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

	public function __construct(controller\Front $frontController)
	{
		$this->frontController = $frontController;
		$this->registry = $this->frontController->getRegistry();

		$this->init();
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
			if (!$model instanceof Model)
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

	public function redirect($url, $exit = true)
	{
		$this->frontController->redirect($url, $exit);
	}

	protected function init()
	{
		$this->initializeView();
	}

	protected function initializeView()
	{
		$this->view = $this->registry->getKit()->makeView();

		if ($this->layoutLocalPath) {
			$layout = $this->registry->getKit()->makeView(null, [
				'localPath' => $this->layoutLocalPath
			]);
			$this->view->setLayout($layout);
		}
	}
}
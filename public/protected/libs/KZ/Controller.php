<?php

namespace KZ;

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
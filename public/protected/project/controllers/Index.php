<?php

namespace controllers;

class Index extends \KZ\Controller
{
	protected function init()
	{
		parent::init();

		$this->view->getLayout()->curLink = 'log';
	}

	public function actionIndex()
	{
		$generalLog = new \tables\GeneralLog();

		$filter = new \models\GeneralLogFilter();
		$this->setAttrsForModels([$filter], ['post', 'get']);

		$grid = new \grids\GeneralLog($this->registry, $generalLog);
		$grid->setFilter($filter);

		$this->render('index/index', [
			'generalLog' => $generalLog,
			'grid' => $grid
		]);
	}

	public function actionSetLoggerActive()
	{
		$mysqlLog = new \tables\GeneralLog();
		$mysqlLog->setLogActive((boolean) $this->request->getParam('value'));

		$this->redirect($this->makeLink('index/index'));
	}

	public function actionCreateKeys()
	{
		$model = new \tables\GeneralLog();

		if (!$model->isKeysCreated())
			$model->createKeys();

		$this->redirect($this->makeLink('index/index'));
	}

	public function actionClearLogs()
	{
		$model = new \tables\GeneralLog();
		$model->clearLogs();

		$this->redirect($this->makeLink('index/index'));
	}
}
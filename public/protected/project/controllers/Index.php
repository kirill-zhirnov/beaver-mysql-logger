<?php

namespace controllers;

class Index extends \KZ\Controller
{
	protected function init()
	{
		parent::init();

		$this->view->getLayout()->curLink = 'generalLog';
		$this->view->getLayout()->assignData([
			'pageTitle' => 'General log'
		]);
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

		$this->flashMessenger->add('Logs was successfully cleared.');
		$this->redirect($this->makeLink('index/index'));
	}

	public function actionExplain()
	{
		$model = new \tables\GeneralLog();

		$argument = $this->request->getParam('argument');
		$commandType = $this->request->getParam('command_type');

		if (!$model->isAllowExplain($commandType, $argument))
			throw new \RuntimeException('This query is not allowed to explain!');

		$query = 'explain ' . $argument;

		$stmt = $model->makeStmt($query);
		$stmt->execute();

		$explain = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$stmt->closeCursor();

		$this->render('index/explain', [
			'explain' => $explain,
			'query' => $argument,
			'queriesInThread' => $model->calcQueriesInThread($this->request->getParam('thread_id'))
		]);
	}
}
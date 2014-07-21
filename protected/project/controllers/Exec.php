<?php

namespace controllers;

class Exec extends \KZ\Controller
{
	public function actionExplain()
	{
		$sql = $this->request->getParam('sql');

		$model = new \models\ExplainQuery(
			$this->registry,
			$this->request->getParam('db'),
			$this->request->getParam('commandType'),
			$sql
		);

		$explain = $model->exec();
		$this->view->assignData([
			'result' => $explain,
			'query' => $sql,
			'error' => $model->getLastException() ? $model->getLastException()->getMessage() : null,
			'queriesInThread' => $model->getGeneralLogModel()
				->calcQueriesInThread($this->request->getParam('threadId'))
		]);

		$tpl =  $explain ? 'exec/explain' : 'errors/modal';
		$this->render($tpl);
	}

	public function actionSql()
	{
		$model = new \models\ExecSqlForm();
		$this->applyGetAttributes($model);
		$model->run = 1;

		if ($this->posted($model) && $model->validate()) {
			$execSql = new \models\ExecSql(
				$this->registry,
				$model->db,
				$model->commandType,
				$model->sql
			);

			$this->view->assignData([
				'result' => $execSql->exec(),
				'error' => $execSql->getLastException() ? $execSql->getLastException()->getMessage() : null,
			]);
		}

		$this->render('exec/sql', [
			'model' => $model
		]);
	}
} 
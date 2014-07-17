<?php

namespace controllers;


class Setup extends \KZ\Controller
{
	protected function init()
	{
		parent::init();

		$this->view->getLayout()->assignData([
			'curLink' => 'setup',
			'pageHeader' => 'Setup mysql connection',
			'pageTitle' => 'Setup mysql connection'
		]);
	}

	public function actionIndex()
	{
		$model = new \models\SetupMysql();

		if ($this->posted($model) && $model->validate()) {
			$model->save();

			$this->flashMessenger->add('Form was successfully saved.');
			$this->redirect($this->makeLink('index/index'));
		}

		$this->render('setup/index', [
			'model' => $model
		]);
	}
} 
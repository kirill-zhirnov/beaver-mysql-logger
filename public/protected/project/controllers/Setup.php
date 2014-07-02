<?php

namespace controllers;


class Setup extends \KZ\Controller
{
	protected function init()
	{
		parent::init();

		$this->view->getLayout()->assignData([
			'curLink' => 'setup',
			'pageHeader' => 'Setup mysql connection'
		]);
	}

	public function actionIndex()
	{
		$model = new \models\SetupMysql();

		if ($this->posted($model) && $model->validate()) {
			$model->save();

			//добавить тут сообщение!
			$this->redirect($this->makeLink('index/index'));
		}

		$this->render('setup/index', [
			'model' => $model
		]);
	}
} 
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
		//если пользователь хочет изменить соединение - то должны быть кнопки домой,
		//если соединения нету - кнопок домой быть не должно!

		$model = new \models\SetupMysql();

		if ($this->posted($model) && $model->validate()) {
			$model->save();

			//добавить тут сообщение!
			$this->redirect($this->makeLink('index/index'));
		}

		echo $this->view->render('setup/index', [
			'model' => $model
		]);
	}
} 
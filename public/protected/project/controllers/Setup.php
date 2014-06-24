<?php

namespace controllers;


class Setup extends \KZ\Controller
{
	public function actionIndex()
	{
		$model = new \models\SetupMysql();

		echo $this->view->render('setup/index', [
			'model' => $model
		]);
	}
} 
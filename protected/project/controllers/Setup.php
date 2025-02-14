<?php

namespace controllers;


use KZ\flashMessenger\interfaces\FlashMessenger;

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
        $mysqlDSN = getenv('MYSQL_DSN') ?: '';
        if (!empty($mysqlDSN)) {
            $this->flashMessenger->add('Setup available only if you dont specify ENV variables.', FlashMessenger::ERROR);
            $this->redirect($this->makeLink('index/index'));
            return;
        }

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
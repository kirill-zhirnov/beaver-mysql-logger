<?php

namespace controllers;

class Index extends \KZ\Controller
{
	public function actionIndex()
	{
		echo $this->view->render('index/index');
	}

	public function actionTest()
	{
		echo $this->view->render('index/test');
	}

	public function actionUpdate()
	{
		header('Content-type: application/json');
		echo json_encode([
			'html' => $this->view->renderPartial('index/test', [
				'content' => 'updated:' . time()
			])
		]);
	}

	public function actionTestForm()
	{
		echo $this->view->render('index/form');
	}

	public function actionSubmitForm()
	{
		header('Content-type: application/json');
		echo json_encode([
			'html' => $this->view->renderPartial('index/form', [
				'val' => 'updated:' . time()
			])
		]);
	}
} 
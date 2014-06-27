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

	public function actionAjaxTest()
	{
		echo $this->view->render('index/ajaxTest');
	}

	public function actionError()
	{
		header('Status: 404 Not found');
		exit();
	}

	public function actionPopup()
	{
		header('Content-type: application/json');
		echo json_encode([
			'html' => $this->view->renderPartial('index/popup')
		]);
	}

	public function actionPopup2()
	{
		header('Content-type: application/json');
		echo json_encode([
			'html' => $this->view->renderPartial('index/popup2')
		]);
	}

	public function actionPopup3()
	{
		header('Content-type: application/json');
		echo json_encode([
			'html' => $this->view->renderPartial('index/popup3')
		]);
	}
} 
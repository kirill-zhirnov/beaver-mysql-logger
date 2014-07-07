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
		$mysqlLog = new \tables\GeneralLog();

		echo $this->view->render('index/index', [
			'mysqlLog' => $mysqlLog
		]);
	}

	public function actionSetLoggerActive()
	{
		$mysqlLog = new \tables\GeneralLog();
		$mysqlLog->setLogActive((boolean) $this->request->getParam('value'));

		$this->redirect($this->makeLink('index/index'));
	}

	public function actionTest()
	{
		$filter = new \models\GeneralLogFilter();
		$grid = new \grids\GeneralLog($this->registry);
		$grid->setFilter($filter);

		var_dump($grid);
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
			]),
			'redirect' => $this->makeLink('index/modalMsgTest')->getLink()
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

	public function actionJsHierarchyTest()
	{
		echo $this->view->render('index/jsHierarchyTest');
	}

	public function actionModalMsgTest()
	{
		echo $this->view->render('index/modalMsgTest');
	}

	public function actionLayoutTest()
	{
		echo $this->view->render('index/layoutTest');
	}
}
<?php

namespace controllers;

class Index extends \KZ\Controller
{
	public function actionIndex()
	{
		echo $this->view->render('index/index');
	}
} 
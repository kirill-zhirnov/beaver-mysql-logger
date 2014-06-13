<?php

namespace controllers;

class Index extends \KZ\Controller
{
	public function actionIndex()
	{
		var_dump(__FILE__);
		var_dump(__DIR__);

		$layout = new Layout(__DIR__);

		$view = new View(__DIR__);
		$view->render('index/index', [
			'a' => 'b',
			'c' => 'd',
			'e' => 'g',
			'h' => 'l'
		]);
	}
} 
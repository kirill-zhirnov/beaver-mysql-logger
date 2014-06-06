<?php

namespace KZ\controller;
use KZ\app\interfaces\Registry;

class Front
{
	/**
	 * This is an Abstract Factory for Controllers instance.
	 *
	 * @var Kit
	 */
	protected $controllerKit;

	/**
	 * @var Registry
	 */
	protected $registry;

	/**
	 * @var \KZ\Controller[]
	 */
	protected $controllersChain = [];

	protected $response;

	//стоп, зачем здесь Request? может его сразу из регистра брать? зачем двойная зависимость?
	//request тут не нужен, тк Request будет в Registry. Если нужно будет отрендерить локальный - можно подменить на время в регистре.
	public function __construct(Kit $controllerKit, Registry $registry)
	{
		$this->controllerKit = $controllerKit;
		$this->registry = $registry;
	}

	public function run()
	{
		$this->makeControllersChain();

//		foreach ($this->controllersChain)
	}

	public function makeControllersChain()
	{}

	public function getControllersChain()
	{
		return $this->controllersChain;
	}

	public function forward($controllerPath, $controller, $action)
	{}


} 
<?php

namespace KZ\controller;
use KZ\app;

class Front
{
	protected $request;

	/**
	 * @var app\interfaces\Registry
	 */
	protected $registry;

	public function __construct($request, app\interfaces\Registry $registry)
	{
		$this->request = $request;
		$this->registry = $registry;
	}

	public function run()
	{}
} 
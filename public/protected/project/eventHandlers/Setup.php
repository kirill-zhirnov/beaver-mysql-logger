<?php

namespace eventHandlers;
use KZ\event,
	KZ\controller
;

class Setup
{
	/**
	 * @var event\interfaces\Event
	 */
	protected $event;

	/**
	 * @var controller\Front
	 */
	protected $controllerFront;

	/**
	 * @param event\interfaces\Event $event
	 * @throws \UnexpectedValueException
	 */
	public function onBeforeRunControllerChain(event\interfaces\Event $event)
	{
		$this->event = $event;
		$this->controllerFront = $event->getSender();

		if (!$this->controllerFront instanceof controller\Front)
			throw new \UnexpectedValueException('Sender must be instance of controller\Front.');

		$this->checkMysql();
	}

	protected function checkMysql()
	{

	}
} 
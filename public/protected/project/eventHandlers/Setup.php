<?php

namespace eventHandlers;
use KZ\event,
	KZ\controller,
	KZ\db
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

		//здесь сделать проверку на контроллера - иначе это вечное перенаправление!!!
		$this->checkMysql();
	}

	protected function checkMysql()
	{
		$mysqlModel = new \models\Mysql();
		$mysql = $mysqlModel->getMysqlConnection();

		$setupRoute = 'setup/index';

		if (is_null($mysql)) {
			$link = $this->controllerFront->makeLink($setupRoute);
			$this->controllerFront->redirect($link->getLink());
		}

		if ($mysql === false) {
			$link = $this->controllerFront->makeLink($setupRoute);
			$this->controllerFront->redirect($link->getLink());
		}

		$this->controllerFront->getRegistry()->getConnectionStorage()
			->add($mysql, db\ConnectionStorage::SQLITE, false)
		;
	}
} 
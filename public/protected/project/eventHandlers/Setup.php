<?php

namespace eventHandlers;
use KZ\event,
	KZ\controller,
	KZ\db,
	KZ\app
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
	 * @var app\interfaces\Registry
	 */
	protected $registry;

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

		$this->registry = $this->controllerFront->getRegistry();

		$this->checkMysql();
	}

	protected function checkMysql()
	{
		foreach ($this->controllerFront->getControllerChain() as $item) {
			if (
				get_class($item['instance']) == 'controllers\Setup'
				&&
				strtolower($item['action']) == 'actionindex'
			)
				return;

			break;
		}

		$mysqlModel = new \models\Mysql();
		$mysql = $mysqlModel->getMysqlConnection();

		$setupRoute = 'setup/index';

		if (is_null($mysql)) {
			$this->controllerFront->redirect(
				$this->controllerFront->makeLink($setupRoute)
			);
		}

		if ($mysql === false) {
			$this->registry->getFlashMessenger()
				->add('Cannot connect to Mysql!', 'error')
			;

			$this->controllerFront->redirect(
				$this->controllerFront->makeLink($setupRoute)
			);
		}

		$this->controllerFront->getRegistry()->getConnectionStorage()
			->add($mysql, db\ConnectionStorage::SQLITE, false)
		;
	}
} 
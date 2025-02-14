<?php

namespace eventHandlers;
use KZ\event,
	KZ\controller,
	KZ\db,
	KZ\app
;
use PDO;
use Exception;

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
	 * @var app\Facade
	 */
	protected $facade;

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

	public function onBeforeInitialize(event\interfaces\Event $event)
	{
		$this->event = $event;
		$this->facade = $event->getSender();

		if (!$this->facade instanceof app\Facade)
			throw new \UnexpectedValueException('Sender must be instance of app\Facade.');

		$this->registry = $this->facade->getRegistry();

		$this->checkSqlitePermissions();
	}

	protected function checkSqlitePermissions()
	{
		$config = $this->registry->getConfig();

		if (!isset($config['components']['db']['connection']['dsn']))
			return;

		$dsn = $config['components']['db']['connection']['dsn'];
		if (preg_match('#^sqlite:(.+)#', $dsn, $matches)) {
			$path = $matches[1];
			$dir = dirname($path);

			if (!is_file($path) && !is_writable($dir)) {
				$view = $this->getViewToShowError();
				echo $view->render('errors/error', [
					'error' => 'Directory "' . $dir . '" is not <b>writable</b>! Please make it readable and writable for this script process.'
				]);

				exit();
			}

			if (!is_file($path))
				return;

			if (!is_readable($path)) {
				$view = $this->getViewToShowError();
				echo $view->render('errors/error', [
					'error' => 'File "' . $path . '" is not <b>readable</b>! Please make it readable and writable for this script process.'
				]);

				exit();
			}

			if (!is_writable($path)) {
				$view = $this->getViewToShowError();
				echo $view->render('errors/error', [
					'error' => 'File "' . $path . '" is not <b>writable</b>! Please make it readable and writable for this script process.'
				]);

				exit();
			}

			if (!is_writable($dir)) {
				$view = $this->getViewToShowError();
				echo $view->render('errors/error', [
					'error' => 'Directory "' . $dir . '" is not <b>writable</b>! Please make it readable and writable for this script process.'
				]);

				exit();
			}
		}
	}

	protected function checkMysql(): void
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

        /** @var string|null $setupLink */
        $setupLink = null;

        if (self::isMySQLDSNSpecified()) {
            $mysql = self::makeConnectionByEnv();
        } else {
            $mysql = (new \tables\MysqlCredentials())->getMysqlConnection();
            $setupLink = $this->controllerFront->makeLink('setup/index');
        }

		if ($mysql === false) {
			$this->registry->getFlashMessenger()
				->add('Cannot connect to Mysql!', 'error')
			;
		}

        if (!$mysql) {
            if ($setupLink) {
                $this->registry->getResponse()->redirect($setupLink);
            } else {
                $this->exitWithError('Cannot connect to MySQL, check env: MYSQL_DSN, MYSQL_USERNAME, MYSQL_PASS');
            }

            return;
        }

		$this->controllerFront->getRegistry()->getConnectionStorage()
			->add($mysql, db\ConnectionStorage::MYSQL, false)
		;
		db\table\Mysql::setDefaultConnection($mysql);
	}

    protected function exitWithError(string $error): void
    {
        echo $this->getViewToShowError()
            ->render('errors/error', ['error' => $error])
        ;

        exit();
    }

	protected function getViewToShowError()
	{
		return $this->registry->getKit()->makeView(realpath(PROTECTED_PATH . '/project/views'));
	}

    public static function isMySQLDSNSpecified(): bool
    {
        return (bool) getenv('MYSQL_DSN');
    }

    public static function makeConnectionByEnv(): PDO|false
    {
        $mysqlDSN = getenv('MYSQL_DSN') ?: '';
        $mysqlUser = getenv('MYSQL_USERNAME') ?: '';
        $mysqlPass = getenv('MYSQL_PASS') ?: '';

        try {
            $pdo = new PDO($mysqlDSN, $mysqlUser, $mysqlPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec('set names utf8');
        } catch (Exception $e) {
            return false;
        }

        return $pdo;
    }
} 
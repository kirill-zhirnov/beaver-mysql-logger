<?php

namespace models;
use KZ\app\interfaces as appInterfaces;

class ExplainQuery extends ExecDbQuery
{
	/**
	 * @var string
	 */
	protected $commandType;

	/**
	 * @var
	 */
	protected $sql;

	/**
	 * @var \tables\GeneralLog
	 */
	protected $generalLogModel;

	/**
	 * @var \Exception
	 */
	protected $lastException;

	public function __construct(appInterfaces\Registry $registry, $dbName, $commandType, $sql)
	{
		$this->registry = $registry;
		$this->dbName = $dbName;
		$this->commandType = $commandType;
		$this->sql = $sql;

		$this->generalLogModel = new \tables\GeneralLog();
	}

	/**
	 * @return array
	 * @throws \RuntimeException
	 */
	public function getExplain()
	{
		if (!$this->generalLogModel->isAllowExplain($this->commandType, $this->sql))
			throw new \RuntimeException('This query is not allowed to explain!');

		$this->setupConnection();

		try {
			$stmt = $this->connection->prepare('explain ' . $this->sql);
			$stmt->execute();

			$explain = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			$stmt->closeCursor();
		} catch (\Exception $e) {
			$explain = false;
			$this->lastException = $e;
		}

		return $explain;
	}

	/**
	 * @return \tables\GeneralLog
	 */
	public function getGeneralLogModel()
	{
		return $this->generalLogModel;
	}

	/**
	 * @return \Exception
	 */
	public function getLastException()
	{
		return $this->lastException;
	}
} 
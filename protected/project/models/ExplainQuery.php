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

		$sql = 'explain ' . $this->sql;

		$stmt = $this->connection->prepare($sql);
		$stmt->execute();

		$explain = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$stmt->closeCursor();

		return $explain;
	}

	/**
	 * @return \tables\GeneralLog
	 */
	public function getGeneralLogModel()
	{
		return $this->generalLogModel;
	}
} 
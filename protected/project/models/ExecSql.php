<?php

namespace models;

class ExecSql extends ExecDbQuery
{
	protected function runQuery()
	{
		if (!$this->generalLogModel->isAllowExecute($this->commandType, $this->sql))
			throw new \RuntimeException('This query is not allowed to explain!');

		$stmt = $this->connection->prepare($this->sql);
		$stmt->execute();

		$out = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$stmt->closeCursor();

		return $out;
	}
} 
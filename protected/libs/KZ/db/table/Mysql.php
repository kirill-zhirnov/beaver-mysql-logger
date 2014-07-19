<?php

namespace KZ\db\table;
use KZ\db;

abstract class Mysql extends db\Table
{
	/**
	 * @var \PDO
	 */
	protected static $defaultConnection;

	/**
	 * @return array
	 */
	public function showDatabases()
	{
		$stmt = $this->makeStmt('show databases');
		$stmt->execute();

		$out = array();
		foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row)
			$out[] = $row['Database'];

		sort($out);

		$stmt->closeCursor();

		return $out;
	}
}
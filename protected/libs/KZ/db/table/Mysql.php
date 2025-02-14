<?php

namespace KZ\db\table;
use KZ\db;
use PDO;

abstract class Mysql extends db\Table
{
	protected static ?PDO $defaultConnection;

	public function showDatabases(): array
	{
		$stmt = $this->makeStmt('show databases');
		$stmt->execute();

		$out = [];
		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
			$out[] = $row['Database'];

		sort($out);

		$stmt->closeCursor();

		return $out;
	}
}
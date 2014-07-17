<?php

namespace KZ\db\table;
use KZ\db;

abstract class SQLite extends db\Table
{
	/**
	 * @var \PDO
	 */
	protected static $defaultConnection;
}
<?php

namespace KZ\db\table;
use KZ\db;

abstract class Mysql extends db\Table
{
	/**
	 * @var \PDO
	 */
	protected static $defaultConnection;
} 
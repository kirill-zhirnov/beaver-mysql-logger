<?php

namespace KZ\db;

class PDOMock extends \PDO
{
	/**
	 * To be able compare objects
	 *
	 * @var string
	 */
	protected $dsn;

	public function __construct($dsn = null)
	{
		$this->dsn = $dsn;
	}
} 
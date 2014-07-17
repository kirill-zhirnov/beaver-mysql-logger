<?php

namespace KZ\db;

class ConnectionStorage implements interfaces\ConnectionStorage
{
	/**
	 * Array with connections. Array structure:
	 * ['name' => ['type' => 'mysql', 'pdo' => $pdo]]
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Name of default connection
	 *
	 * @var string
	 */
	protected $defaultConnection;

	public function add(\PDO $connection, $type, $name = null, $isDefault = false)
	{
		if (is_null($name))
			$name = $type . sizeof($this->data);

		$this->data[$name] = [
			'type' => $type,
			'pdo' => $connection
		];

		if ($isDefault)
			$this->defaultConnection = $name;

		return $name;
	}

	public function getByType($type)
	{
		$out = [];

		foreach ($this->data as $name => $row)
			if ($row['type'] == $type)
				$out[$name] = $row['pdo'];

		return $out;
	}

	public function getByName($name)
	{
		return isset($this->data[$name]) ? $this->data[$name]['pdo'] : null;
	}

	public function getDefault()
	{
		return $this->defaultConnection ? $this->getByName($this->defaultConnection) : null;
	}
} 
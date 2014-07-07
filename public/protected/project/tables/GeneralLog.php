<?php

namespace tables;
use KZ\db\table;

class GeneralLog extends table\Mysql
{
	/**
	 * Return table name.
	 *
	 * @return string
	 */
	public function getTableName()
	{
		return 'general_log';
	}

	/**
	 * Primary keys fields.
	 *
	 * @return array
	 */
	public function getPk()
	{
		return false;
	}

	/**
	 * Switch logger on/off
	 *
	 * @param bool $value
	 * @return $this
	 */
	public function setLogActive($value)
	{
		if ($value) {
			$stmt = $this->makeStmt("set global log_output = 'table'");
			$stmt->execute();

			$stmt = $this->makeStmt("set global general_log = 'on'");
			$stmt->execute();
		} else {
			$stmt = $this->makeStmt("set global general_log = 'off'");
			$stmt->execute();
		}

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isLogActive()
	{
		$vars = $this->getLogVariables();

		if (strtolower($vars['general_log']) == 'on'
			&& strtolower($vars['log_output']) == 'table')
			return true;

		return false;
	}

	/**
	 * @return array 'key' => 'value'
	 */
	public function getLogVariables()
	{
		$stmt = $this->makeStmt("
			show variables where Variable_name in ('log_output', 'general_log')
		");
		$stmt->execute();

		$out = [];
		foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row)
			$out[$row['Variable_name']] = $row['Value'];

		$stmt->closeCursor();

		return $out;
	}

	public function getCommandTypeOptions()
	{
		$stmt = $this->makeStmt("select distinct command_type from general_log");
		$stmt->execute();

		$out = array_column($stmt->fetchAll(\PDO::FETCH_ASSOC), 'command_type', 'command_type');
		$stmt->closeCursor();

		return $out;
	}
} 
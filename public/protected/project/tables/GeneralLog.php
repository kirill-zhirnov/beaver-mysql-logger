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

	public function showKeys()
	{
		$stmt = $this->makeStmt("
			show keys from general_log
		");
		$stmt->execute();

		$out = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$stmt->closeCursor();

		return $out;
	}

	public function createKeys()
	{
		$loggerIsActive = $this->isLogActive();

		if ($loggerIsActive)
			$this->setLogActive(false);

		$stmt = $this->makeStmt("
			alter table general_log engine = MyISAM;
		");
		$stmt->execute();
		$stmt->closeCursor();

		$stmt = $this->makeStmt("
			alter table general_log
				add index debugThreadId (thread_id, event_time),
				add index debugEventTime (event_time),
				add index debugCommandType (command_type)
		");
		$stmt->execute();
		$stmt->closeCursor();

		if ($loggerIsActive)
			$this->setLogActive(true);
	}

	public function isKeysCreated()
	{
		$requiredKeys = [
			'debugThreadId' => 1,
			'debugEventTime' => 1,
			'debugCommandType' => 1
		];

		foreach ($this->showKeys() as $row)
			if (isset($requiredKeys[$row['Key_name']]))
				unset($requiredKeys[$row['Key_name']]);

		return empty($requiredKeys);
	}
} 
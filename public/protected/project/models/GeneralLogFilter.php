<?php

namespace models;

class GeneralLogFilter extends \KZ\model\Filter
{
	public $threadId;

	public $serverId;

	public $commandType;

	public $argument;

	public $sortBy = 'default';

	public $p = 1;

	protected $commandTypeOptions;

	public function rules()
	{
		return [
			'threadId' => [],
			'serverId' => [],
			'commandType' => [
				['validateCommandType']
			],
			'argument' => [],
			'sortBy' => [
				['validateSortBy']
			],
			'p' => []
		];
	}

	public function validateCommandType($attr)
	{
		if (!array_key_exists($this->commandType, $this->getCommandTypeOptions()))
			$this->addError($attr, 'Incorrect value');
	}

	public function getCommandTypeOptions(array $out = [])
	{
		if (!isset($this->commandTypeOptions)) {
			$table = new \tables\GeneralLog();
			$this->commandTypeOptions = $table->getCommandTypeOptions();
		}

		return array_merge($out, $this->commandTypeOptions);
	}

	public function validateSortBy($attr)
	{
		if (!array_key_exists($this->sortBy, $this->getSortByOptions()))
			$this->addError($attr, 'Incorrect value');
	}

	public function getSortByOptions(array $out = [])
	{
		return array_merge($out, [
			'default' => 'Default',
			'event_time' => 'Event time',
			'user_host' => 'User host',
			'thread_id' => 'Thread id',
			'server_id' => 'Server id',
			'command_type' => 'Command type',
			'argument' => 'Argument'
		]);
	}
} 
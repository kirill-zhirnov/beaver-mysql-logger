<?php

namespace models;

class ExecSqlForm extends \KZ\Model
{
	public $threadId;

	public $commandType;

	public $sql;

	public $db;

	public $run;

	public function rules()
	{
		return [
			'threadId' => [
				['required']
			],
			'commandType' => [
				['required']
			],
			'sql' => [
				['required']
			],
			'db' => [
				['required']
			],
			'run' => [
				['required']
			],
		];
	}
} 
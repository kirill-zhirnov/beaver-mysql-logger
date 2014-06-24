<?php

namespace models;

class SetupMysql extends \KZ\Model
{
	public $dsn;

	public $username;

	public $password;

	public $options;

	public function __construct()
	{
		$mysql = new Mysql();
		$row = $mysql->find();

		if ($row)
			$this->setAttributes([
				'dsn' => $row['mysql_dsn'],
				'username' => $row['mysql_username'],
				'password' => $row['mysql_password'],
				'options' => $row['mysql_options']
			]);
	}

	public function rules()
	{
		return [
			'username' => [
				['required']
			],
			'password' => [
				['required']
			],
			'options' => [
				['validateJson']
			],
			'dsn' => [
				['required'],
				['validateConnection']
			],
		];
	}

	public function save()
	{

	}

	public function validateConnection($attribute)
	{
		if ($this->getErrors())
			return;

		try {
			$options = $this->options ? json_decode($this->options, true) : [];
			$pdo = new \PDO($this->dsn, $this->username, $this->password, $options);
			$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$pdo->exec('set names utf8');
		} catch (\Exception $e) {
			$this->addError($attribute, 'Cannot connect to Mysql, with error: "' . $e->getMessage() . '".');
		}
	}

	public function validateJson($attribute)
	{
		$options = trim($this->options);

		if (!$options)
			return;

		$result = json_decode($options, true);
		if (!$result || !is_array($result))
			$this->addError($attribute, 'It is not valid JSON!');
	}
}
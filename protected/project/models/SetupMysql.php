<?php

namespace models;

class SetupMysql extends \KZ\Model
{
	public $dsn;

	public $username;

	public $password;

	public $options;

	/**
	 * Model
	 *
	 * @var \tables\MysqlCredentials
	 */
	protected $mysql;

	/**
	 * @var array
	 */
	protected $mysqlRow;

	public function __construct()
	{
		$this->mysql = new \tables\MysqlCredentials();
		$this->mysqlRow = $this->mysql->find();

		if ($this->mysqlRow)
			$this->setAttributes([
				'dsn' => $this->mysqlRow['mysql_dsn'],
				'username' => $this->mysqlRow['mysql_username'],
				'password' => $this->mysqlRow['mysql_password'],
				'options' => $this->mysqlRow['mysql_options']
			]);

		parent::__construct();
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
		$columns = [
			'mysql_dsn' => $this->dsn,
			'mysql_username' => $this->username,
			'mysql_password' => $this->password,
			'mysql_options' => $this->options
		];

		if ($this->mysqlRow)
			$this->mysql->updateByPk([$this->mysqlRow['mysql_id']], $columns);
		else
			$this->mysql->insert($columns);

		return $this;
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
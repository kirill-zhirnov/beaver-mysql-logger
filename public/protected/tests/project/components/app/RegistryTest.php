<?php

namespace project\components\app;
use KZ\db;

class RegistryTest extends \PHPUnit_Framework_TestCase
{
	public function testConnectionStorage()
	{
		$sqlite = new db\PDOMock('sqlite');
		$mysql = new db\PDOMock('mysql');

		$cs = new db\ConnectionStorage();
		$cs->add($sqlite, db\ConnectionStorage::SQLITE, 'db', true);
		$cs->add($mysql, db\ConnectionStorage::MYSQL, null, false);

		$registry = new \components\app\Registry();
		$registry->connectionStorage = $cs;

		$this->assertEquals($cs, $registry->getConnectionStorage());
		$this->assertEquals($sqlite, $registry->getDb());
		$this->assertEquals($mysql, $registry->getMysql());
	}
}
 
<?php

namespace KZ\app;

use KZ\db,
	KZ\app;

class RegistryTest extends \PHPUnit_Framework_TestCase
{
	public function testConnectionStorage()
	{
		$sqlite = new db\PDOMock('sqlite');

		$cs = new db\ConnectionStorage();
		$cs->add($sqlite, db\ConnectionStorage::SQLITE, 'db', true);

		$registry = new app\Registry();
		$registry->connectionStorage = $cs;

		$this->assertEquals($cs, $registry->getConnectionStorage());
		$this->assertEquals($sqlite, $registry->getDb());
	}

	public function testKit()
	{
		$kit = new app\Kit([]);

		$registry = new app\Registry();
		$registry->setKit($kit);

		$this->assertEquals($kit, $registry->getKit());
	}
} 
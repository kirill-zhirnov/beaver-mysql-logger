<?php

namespace KZ\db;

class TableTest extends \PHPUnit_Framework_TestCase
{
	public function testDifferentDefaultConnection()
	{
		$pdoA = $this->getMock('\KZ\db\PDOMock', null, ['dsnA']);
		$pdoB = $this->getMock('\KZ\db\PDOMock', null, ['dsnB']);

		table\Mysql::setDefaultConnection($pdoA);
		table\SQLite::setDefaultConnection($pdoB);

		$this->assertEquals('dsnA', table\Mysql::getDefaultConnection()->getDsn());
		$this->assertEquals('dsnB', table\SQLite::getDefaultConnection()->getDsn());
	}
}
 
<?php

namespace KZ\grid;


class PagerTest extends \PHPUnit_Framework_TestCase
{
	public function testInterfaces()
	{
		$pager = new Pager(100);
		$this->assertEquals(100, $pager->getItemCount());

		$pager->setPageSize(100);
		$this->assertEquals(100, $pager->getPageSize());
	}

	public function testCalc()
	{
		$pager = new Pager(27);
		$pager
			->setPageSize(4)
			->setCurrentPage(7)
		;

		$this->assertEquals(4, $pager->getLimit());
		$this->assertEquals(24, $pager->getOffset());
		$this->assertEquals(7, $pager->getPageCount());

		$pager->setCurrentPage(1);
		$this->assertEquals(0, $pager->getOffset());
	}

	public function testUnavailablePage()
	{
		$pager = new Pager(27);
		$pager
			->setPageSize(4)
			->setCurrentPage(10) // this page is un-available!
		;

		$this->assertEquals(0, $pager->getOffset());
		$this->assertEquals(1, $pager->getCurrentPage());
	}
}
 
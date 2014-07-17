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

	public function testPagesInRange()
	{
		$pager = new Pager(100);
		$pager
			->setPageSize(2)
			->setPageRange(5)
		;

		$this->assertEquals([1,2,3,4,5], $pager->getPagesInRange());

		$pager->setCurrentPage(50);
		$this->assertEquals([46,47,48,49,50], $pager->getPagesInRange());

		$pager->setCurrentPage(30);
		$this->assertEquals([28,29,30,31,32], $pager->getPagesInRange());

		$pager = new Pager(10);
		$pager
			->setPageSize(5)
			->setPageRange(5)
		;
		$this->assertEquals([1,2], $pager->getPagesInRange());
	}

	public function testEmptyPager()
	{
		$pager = new Pager(0);
		$pager
			->setPageSize(2)
			->setPageRange(5)
		;

		$this->assertEquals([], $pager->getPagesInRange());
	}
}
 
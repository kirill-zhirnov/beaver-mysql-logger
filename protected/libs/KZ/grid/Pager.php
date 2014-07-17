<?php

namespace KZ\grid;

class Pager implements interfaces\Pager
{
	/**
	 * @var int Item count
	 */
	protected $itemCount;

	/**
	 * @var int Number of items per page
	 */
	protected $pageSize = 10;

	/**
	 * @var int Current page
	 */
	protected $currentPage = 1;

	protected $pageRange = 5;

	/**
	 * @param int $itemCount
	 * @throws \InvalidArgumentException
	 */
	public function __construct($itemCount)
	{
		$this->itemCount = (int) $itemCount;

		if ($this->itemCount < 0)
			throw new \InvalidArgumentException('Item count must be greater than 0 or equal.');
	}

	/**
	 * @return int
	 */
	public function getItemCount()
	{
		return $this->itemCount;
	}

	/**
	 * Set number of items per page.
	 *
	 * @param int $pageSize
	 * @throws \InvalidArgumentException
	 * @return $this
	 */
	public function setPageSize($pageSize)
	{
		$this->pageSize = (int) $pageSize;

		if ($this->pageSize <= 0)
			throw new \InvalidArgumentException('Page size must be greater than 0.');

		return $this;
	}

	/**
	 * Get number of items per page.
	 *
	 * @return int
	 */
	public function getPageSize()
	{
		return $this->pageSize;
	}

	/**
	 * Return current page.
	 *
	 * @return int
	 */
	public function getCurrentPage()
	{
		if ($this->currentPage > $this->getPageCount())
			$this->currentPage = 1;

		return $this->currentPage;
	}

	/**
	 * Set current page.
	 *
	 * @param int $page
	 * @throws \InvalidArgumentException
	 * @return $this
	 */
	public function setCurrentPage($page)
	{
		$this->currentPage = (int) $page;

		if ($this->currentPage <= 0)
			throw new \InvalidArgumentException('Current page must be greater than 0.');

		return $this;
	}

	/**
	 * @return int - limit for SQL
	 */
	public function getLimit()
	{
		return $this->pageSize;
	}

	/**
	 * @return int - limit for SQL
	 */
	public function getOffset()
	{
		return $this->pageSize * ($this->getCurrentPage() - 1);
	}

	/**
	 * Return number of pages.
	 *
	 * @return int
	 */
	public function getPageCount()
	{
		return (int) ceil($this->itemCount / $this->pageSize);
	}

	/**
	 * @param int $range
	 * @return $this
	 */
	public function setPageRange($range)
	{
		$this->pageRange = $range;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getPagesInRange()
	{
		$start = max(1, $this->getCurrentPage() - intval($this->pageRange / 2));
		$end = $start + $this->pageRange - 1;

		if ($end > $this->getPageCount()) {
			$end = $this->getPageCount();
			$start = max(1, $end - $this->pageRange + 1);
		}

		if (!$end)
			return [];

		return array_keys(array_fill($start, $end - $start + 1, 1));
	}
}
<?php

namespace KZ\grid\interfaces;

/**
 * Interface Pager
 * @package KZ\interfaces
 */
interface Pager
{
	/**
	 * @param int $itemCount
	 */
	public function __construct($itemCount);

	/**
	 * @return int
	 */
	public function getItemCount();

	/**
	 * Set number of items per page.
	 *
	 * @param int $pageSize
	 * @return $this
	 */
	public function setPageSize($pageSize);

	/**
	 * Get number of items per page.
	 *
	 * @return int
	 */
	public function getPageSize();

	/**
	 * Return current page.
	 *
	 * @return int
	 */
	public function getCurrentPage();

	/**
	 * Set current page.
	 *
	 * @param int $page
	 * @return $this
	 */
	public function setCurrentPage($page);

	/**
	 * @return int - limit for SQL
	 */
	public function getLimit();

	/**
	 * @return int - limit for SQL
	 */
	public function getOffset();

	/**
	 * Return number of pages.
	 *
	 * @return int
	 */
	public	function getPageCount();

	/**
	 * @param int $range
	 * @return $this
	 */
	public function setPageRange($range);

	/**
	 * @return array
	 */
	public function getPagesInRange();
} 
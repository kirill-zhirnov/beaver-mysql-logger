<?php

namespace KZ\grid\interfaces;

/**
 * Interface Grid
 * @package KZ\grid\interfaces
 */
interface Grid
{
	/**
	 * @param \KZ\app\Registry $registry
	 * @param \KZ\db\interfaces\TableModel $table
	 */
	public function __construct(\KZ\app\Registry $registry, \KZ\db\interfaces\TableModel $table);

	/**
	 * @return \KZ\app\Registry
	 */
	public function getRegistry();

	/**
	 * @return \KZ\db\interfaces\TableModel
	 */
	public function getTable();

	/**
	 * @return array
	 */
	public function getRows();

	/**
	 * @return Pager
	 */
	public function getPager();
}
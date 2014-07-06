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
	 */
	public function __construct(\KZ\app\Registry $registry);

	/**
	 * @return \KZ\app\Registry
	 */
	public function getRegistry();

	/**
	 * @return array
	 */
	public function getRows();

	/**
	 * @return Pager
	 */
	public function getPager();
}
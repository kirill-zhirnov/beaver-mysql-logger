<?php

namespace KZ\app\interfaces;

/**
 * This is an Abstract Factory for Application. It makes basic components.
 *
 * Interface Kit
 * @package KZ\app\interfaces
 */
interface Kit
{
	/**
	 * Create connectionStorage and fill it with PDO objects.
	 *
	 * @return \KZ\db\interfaces\ConnectionStorage
	 */
	public function makeConnectionStorage();

	/**
	 * Create registry for application.
	 *
	 * @return \KZ\app\interfaces\Registry
	 */
	public function makeRegistry();
} 
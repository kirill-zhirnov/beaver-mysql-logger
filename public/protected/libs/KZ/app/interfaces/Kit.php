<?php

namespace KZ\app\interfaces;

/**
 * This is a Abstract Factory for Application. It makes basic components.
 *
 * Interface Kit
 * @package KZ\app\interfaces
 */
interface Kit
{
	/**
	 * Create connectionStorage and fill it with PDO objects.
	 *
	 * @return \KZ\db\ConnectionStorage
	 */
	public function makeConnectionStorage();
} 
<?php

namespace KZ\view\helpers;

use KZ\view;

class FlashMessenger extends view\Helper
{
	/**
	 * @return \KZ\flashMessenger\interfaces\FlashMessenger
	 */
	public function get()
	{
		return $this->getRegistry()->getFlashMessenger();
	}
} 
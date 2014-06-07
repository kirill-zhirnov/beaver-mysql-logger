<?php

namespace KZ\controller\interfaces;

interface Chain extends \Iterator
{
	public function __construct(array $controllers);

	public function push(\KZ\Controller $controller, $action);

	public function pushAfterCurrent(\KZ\Controller $controller, $action);

	public function pushAfterPosition($position, \KZ\Controller $controller, $action);
} 
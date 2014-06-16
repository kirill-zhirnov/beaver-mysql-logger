<?php

namespace KZ\events\interfaces;

interface Event
{
	public function __construct(array $params);

	public function isDefaultPrevented();

	public function getParam();

	public function hasParam();

	public function setParam();
} 
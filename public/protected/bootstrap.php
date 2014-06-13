<?php

defined('PROTECTED_PATH') || define('PROTECTED_PATH', __DIR__);

//set include path and setup default autoload
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, [
	PROTECTED_PATH . '/libs',
	PROTECTED_PATH . '/project',
]));
spl_autoload_register();

$config = require 'config/main.php';

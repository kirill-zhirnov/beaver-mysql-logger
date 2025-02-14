<?php

session_start();

defined('PROTECTED_PATH') || define('PROTECTED_PATH', __DIR__);

//set include path and setup default autoload
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, [
	PROTECTED_PATH . '/libs',
	PROTECTED_PATH . '/project',
]));

spl_autoload_register(function($className) {
	$className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
	$filePath = $className . '.php';

	@include_once $filePath;
});

$config = require 'config/main.php';

//to prevent warning about timezone
if (ini_get('date.timezone') == '') {
    date_default_timezone_set('UTC');
}

return $config;
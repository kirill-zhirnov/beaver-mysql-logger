<?php

require_once 'protected/bootstrap.php';

var_dump(realpath(realpath(realpath(__FILE__))));
echo 13;
//$facade = new \KZ\app\Facade\Http($config);
//$facade->run();
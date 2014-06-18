<?php

require_once 'protected/bootstrap.php';

$facade = new \KZ\app\Facade\Http($config);

$chain = new \KZ\Controller\Chain();
foreach ($chain as $key => $val)
	var_dump($val);
//$facade->run();
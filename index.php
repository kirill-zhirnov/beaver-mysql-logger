<?php

require_once 'protected/bootstrap.php';

$facade = new \KZ\app\Facade\Http($config);
$facade->run();
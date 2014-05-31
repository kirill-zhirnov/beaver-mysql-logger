<?php

require_once 'protected/bootstrap.php';

$kit = new \KZ\app\Kit($config);

$connectionStorage = $kit->makeConnectionStorage();

$registry = new \KZ\app\Registry();
$registry->setConnectionStorage($connectionStorage);

var_dump($registry);
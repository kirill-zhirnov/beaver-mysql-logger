<?php

require_once 'protected/bootstrap.php';

$kit = new \KZ\app\Kit($config);

$connectionStorage = $kit->makeConnectionStorage();

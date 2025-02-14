<?php

$config = require_once 'protected/bootstrap.php';

(new \KZ\app\Facade\Http($config))->run();

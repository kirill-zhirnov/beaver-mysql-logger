<?php

require_once 'protected/bootstrap.php';

require_once dirname(__FILE__).'/protected/libs/array_column/array_column.php';

$facade = new \KZ\app\Facade\Http($config);
$facade->run();

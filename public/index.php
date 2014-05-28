<?php

require_once 'protected/bootstrap.php';

$obj = new \KZ\db\PDOMock;
var_dump($obj, get_include_path());
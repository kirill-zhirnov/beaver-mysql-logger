<?php

//require_once 'protected/bootstrap.php';

class A {
	public function b()
	{}
}

$parts = [
	'index/index',
	'path/subpath/subsubpath/action',
	'bad/s',
	'a9_/___'
];

foreach ($parts as $part) {
	preg_match('#^(.*)(?:^|\/)([a-z\d_]+)\/([\w]+)$#i', $part, $matches);
	var_dump($matches);
	echo "<br/>";
}


function test_t()
{}
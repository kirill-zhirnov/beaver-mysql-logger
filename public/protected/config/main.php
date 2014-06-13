<?php

return [
	'db' => [
		'dsn' => 'sqlite:' . realpath(__DIR__ . '/../') . '/db.sq3',
		'username' => null,
		'password' => null,
		'options' => []
	],

	'components' => [
		'httpControllerKit' => [
			'path' => realpath(PROTECTED_PATH . '/project/controllers')
		]
	]
];
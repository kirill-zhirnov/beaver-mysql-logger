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
		],

		'view' => [
			'templatesPath' => realpath(PROTECTED_PATH . '/project/views'),
		],

		'kit' => [
			'class' => '\components\app\Kit'
		],

		'registry' => [
			'class' => '\components\app\Registry'
		],

		'observer' => [
			'events' => [
				[
					'KZ\controller\Front',
					'beforeRunControllerChain',
					function($event) {
						$handler = new \eventHandlers\Setup();
						$handler->onBeforeRunControllerChain($event);
					}
				]
			]
		]
	]
];
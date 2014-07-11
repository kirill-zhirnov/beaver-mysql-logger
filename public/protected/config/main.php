<?php

return [
	'components' => [
		'db' => [
			'connection' => [
				'dsn' => 'sqlite:' . realpath(__DIR__ . '/../') . '/db.sq3',
				'username' => null,
				'password' => null,
				'options' => [],
			],
			'type' => \KZ\db\interfaces\ConnectionStorage::SQLITE,
			'tableModelClass' => '\KZ\db\table\SQLite'
		],

		'httpControllerKit' => [
			'path' => realpath(PROTECTED_PATH . '/project/controllers')
		],

		'view' => [
			'templatesPath' => realpath(PROTECTED_PATH . '/project/views'),
			'config' => [
				'helperKit' => [
					'config' => [
						'helpers' => [
							'sqlformatter' => '\helpers\SqlFormatter'
						]
					]
				]
			]
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
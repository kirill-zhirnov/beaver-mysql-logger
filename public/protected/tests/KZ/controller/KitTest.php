<?php

namespace KZ\controller;
use KZ\controller;

class KitTest extends \PHPUnit_Framework_TestCase
{
	public function testSetConfig()
	{
		$kit = new controller\Kit('path', [
			'path' => 'new',
			'namespacePrefix' => 'testPrefix',
			'suffix' => 'testSuffix',
			'actionPrefix' => 'preffix'
		]);

		$this->assertEquals('path', $kit->getPath());
		$this->assertEquals('testPrefix', $kit->getNamespacePrefix());
		$this->assertEquals('preffix', $kit->getActionPrefix());
	}

	public function testCreateClassName()
	{
		$kit = new controller\Kit('path', ['namespacePrefix' => 'testPrefix']);

		$this->assertEquals('testPrefix\path\sub\Controller', $kit->createClassName('/path/SUB', 'ControLLer'));

		$kit = new controller\Kit('path', ['namespacePrefix' => '']);
		$this->setExpectedException('UnexpectedValueException', 'Namespace prefix must be non empty by security reasons.');
		$kit->createClassName('/path/SUB', 'ControLLer');
	}
} 
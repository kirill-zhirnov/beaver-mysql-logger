<?php

namespace KZ;

class ViewTest extends \PHPUnit_Framework_TestCase
{
	public function testInit()
	{
		$layout = new View('layout');

		$view = new View('/path/', [
			'templatesPath' => 'bbb',
			'extension' => '.ccc',
			'layout' => $layout,
			'varNameForContent' => 'bigContent'
		]);

		$this->assertEquals('/path', $view->getTemplatesPath());
		$this->assertEquals('.ccc', $view->getExtension());
		$this->assertEquals($view->getLayout(), $layout);
		$this->assertEquals('bigContent', $view->getVarNameForContent());
	}

	public function testAbsolutePath()
	{
		$view = new View('/path/');

		$this->assertEquals('/path/sub/path.php', $view->getAbsoluteTemplatePath('sub/path'));
		$this->assertEquals('/absolute/path.php', $view->getAbsoluteTemplatePath('/absolute/path'));
		$this->assertEquals('C:\\path\\sub\\test.php', $view->getAbsoluteTemplatePath('C:\\path\\sub\\test'));
	}

	public function testAssignData()
	{
		$view = new View('/path/');

		$this->assertEquals([], $view->getData());

		$data = [
			'a' => 'b',
			'c' => 'd'
		];

		$view->assignData($data, $view->getData());
	}

	public function testSetDataViaMethods()
	{
		$view = new View('/path/');

		$this->assertFalse($view->isSetDataViaMethods());
		$view->templatesPath = 'aaaa';

		$this->assertEquals('/path', $view->getTemplatesPath());
		$this->assertEquals($view->getData(), [
			'templatesPath' => 'aaaa'
		]);
	}

	public function testOffsetSet()
	{
		$view = new View('/path/');
		$this->setExpectedException('OutOfBoundsException', 'Incorrect key name: "this".');
		$view['this'] = 'a';

		$this->setExpectedException('OutOfBoundsException', 'Incorrect key name: "this".');
		$view->this = 'a';
	}

	public function testRenderPartial()
	{
		$this->createTmpTpl(__DIR__ . '/tpl.php', '<html><?=$a?>,<?=intval(method_exists($this, "renderPartial"))?>');

		$view = new View(__DIR__);
		$result = $view->renderPartial('tpl', [
			'a' => 'b'
		]);

		$this->assertEquals('<html>b,1', $result);
	}

	public function testRenderNoLayout()
	{
		$this->createTmpTpl(__DIR__ . '/tpl.php', '<html><?=$a?>,<?=intval(method_exists($this, "renderPartial"))?>');

		$view = new View(__DIR__);
		$result = $view->render('tpl', [
			'a' => 'b'
		]);

		$this->assertEquals('<html>b,1', $result);
	}

	public function testRenderWithLayout()
	{
		$this->createTmpTpl(__DIR__ . '/layout.php', '<html><?=$content?></html>');
		$this->createTmpTpl(__DIR__ . '/view.php', '<b><?=$test?></b>');

		$layout = new View(__DIR__);
		$layout->setLocalPath('layout');

		$view = new View(__DIR__);
		$view->setLayout($layout);

		$this->assertEquals('<html><b>123</b></html>', $view->render('view', [
			'test' => '123'
		]));
	}

	protected function createTmpTpl($path, $content)
	{
		if (file_exists($path))
			unlink($path);

		file_put_contents($path, $content);
	}
} 
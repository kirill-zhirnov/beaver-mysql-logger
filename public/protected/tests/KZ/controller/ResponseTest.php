<?php

namespace KZ\controller;


class ResponseTest extends \PHPUnit_Framework_TestCase
{
	public function testSimpleRender()
	{
		$request = $this->makeRequest();
		$request->expects($this->once())
			->method('isAjaxRequest')
			->will($this->returnValue(false))
		;

		$view = $this->makeView();
		$view->expects($this->once())
			->method('render')
			->will($this->returnValue('tpl output'))
		;

		$controller = $this->makeController($view);

		$this->expectOutputString('tpl output');

		$response = new Response($request);
		$response->setController($controller);
		$response->render('tpl');
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testAjaxRender()
	{
		$request = $this->makeRequest();
		$request->expects($this->once())
			->method('isAjaxRequest')
			->will($this->returnValue(true))
		;

		$view = $this->makeView();
		$view->expects($this->once())
			->method('renderPartial')
			->will($this->returnValue('tpl output'))
		;

		$controller = $this->makeController($view);

		$this->expectOutputString(json_encode(['html' => 'tpl output']));

		$response = new Response($request);
		$response->setController($controller);
		$response->render('tpl');

		$this->assertContains('Content-type: application/json', xdebug_get_headers());
	}

	public function testRenderException()
	{
		$request = $this->makeRequest();
		$response = new Response($request);

		$this->setExpectedException('UnderflowException', 'You must set controller before calling this method.');

		$response->render('tpl');
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testRedirect()
	{
		$response = new Response($this->makeRequest());
		$response->exitAfterRedirect(false);

		$response->redirect('test.php', false);
		$this->assertContains('Location: test.php', xdebug_get_headers());
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testJson()
	{
		$response = new Response($this->makeRequest());
		$response->setJson(['a' => 'b']);

		$this->expectOutputString(json_encode(['a' => 'b', 'c' => 'd']));
		$response->json(['c' => 'd']);

		$this->assertContains('Content-type: application/json', xdebug_get_headers());
	}

	/**
	 * @param string $route
	 * @return \KZ\controller\Request
	 */
	protected function makeRequest($route = '')
	{
		return $this->getMock('\KZ\controller\Request', ['getScriptName', 'isAjaxRequest'], [$route]);
	}

	/**
	 * @param \KZ\View $view
	 * @return \KZ\Controller
	 */
	protected function makeController(\KZ\View $view = null)
	{
		if (!$view)
			$view = $this->makeView();

		$mock = $this->getMockBuilder('\KZ\Controller')
			->disableOriginalConstructor()
			->setMethods(null)
			->getMock()
		;

		$mock->setView($view);

		return $mock;
	}

	/**
	 * @return \KZ\View
	 */
	protected function makeView()
	{
		return $this->getMock('\KZ\View', ['render', 'renderPartial'], ['test']);
	}
}
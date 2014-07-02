<?php

namespace KZ\controller;

use KZ\Controller,
	KZ\link\interfaces\Link
;

class Response implements interfaces\Response
{
	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var Controller
	 */
	protected $controller;

	/**
	 * @var array
	 */
	protected $json = [];

	/**
	 * @var bool
	 */
	protected $exitAfterRedirect = true;

	/**
	 * @param interfaces\Request $request
	 */
	public function __construct(interfaces\Request $request)
	{
		$this->request = $request;
	}

	/**
	 * Set controller to have access to view instance.
	 *
	 * @param \KZ\Controller $controller
	 * @return $this
	 */
	public function setController(Controller $controller)
	{
		$this->controller = $controller;

		return $this;
	}

	/**
	 * Render template. If it is ajax request - result will be
	 * put in 'html' => $result and outputted as JSON.
	 *
	 * If it is simple request from browser - result will be outputted as string.
	 *
	 * @param $localPath
	 * @param array $data
	 * @throws \UnderflowException
	 * @return void
	 */
	public function render($localPath, array $data = [])
	{
		if (!$this->controller)
			throw new \UnderflowException('You must set controller before calling this method.');

		$view = $this->controller->getView();

		if ($this->request->isAjaxRequest())
			$this->json([
				'html' => $view->renderPartial($localPath, $data)
			]);
		else
			echo $view->render($localPath, $data);
	}

	/**
	 * if it is ajax request - will be outputted json: 'redirect' => 'url'
	 * if it is simple request - header "Location" will be sent.
	 *
	 * @param \KZ\link\interfaces\Link|string $url
	 * @return void
	 */
	public function redirect($url)
	{
		if ($url instanceof Link)
			$url = $url->getLink();

		if ($this->request->isAjaxRequest())
			$this->json(['redirect' => $url]);
		else
			header('Location: ' . $url);

		if ($this->exitAfterRedirect)
			exit();
	}

	/**
	 * Set additional data to ajax response.
	 *
	 * @param array $json
	 * @return $this
	 */
	public function setJson(array $json)
	{
		$this->json = array_replace($this->json, $json);

		return $this;
	}

	/**
	 * Output Json with special headers.
	 *
	 * @param array $json
	 * @return void
	 */
	public function json(array $json = [])
	{
		header('Content-type: application/json');

		$this->setJson($json);
		echo json_encode($this->json);
	}

	/**
	 * @param boolean $value
	 * @return $this
	 */
	public function exitAfterRedirect($value)
	{
		$this->exitAfterRedirect = (boolean) $value;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isExitAfterRedirect()
	{
		return $this->exitAfterRedirect;
	}
} 
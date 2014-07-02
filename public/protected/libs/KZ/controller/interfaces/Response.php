<?php

namespace KZ\controller\interfaces;

use KZ\Controller;

/**
 * Prepare response for different type of requests.
 * For example:
 * For ajax request - it will renderPartial given template, put it in json and output as JSON.
 * For simple request - it will render given template and output it.
 *
 * Interface Response
 * @package KZ\controller\interfaces
 */
interface Response
{
	/**
	 * @param Request $request
	 */
	public function __construct(Request $request);

	/**
	 * Set controller to have access to view instance.
	 *
	 * @param \KZ\Controller $controller
	 * @return $this
	 */
	public function setController(Controller $controller);

	/**
	 * Render template. If it is ajax request - result will be
	 * put in 'html' => $result and outputted as JSON.
	 *
	 * If it is simple request from browser - result will be outputted as string.
	 *
	 * @param $localPath
	 * @param array $data
	 * @return void
	 */
	public function render($localPath, array $data = []);

	/**
	 * if it is ajax request - will be outputted json: 'redirect' => 'url'
	 * if it is simple request - header "Location" will be sent.
	 *
	 * @param \KZ\link\interfaces\Link|string $url
	 * @return void
	 */
	public function redirect($url);

	/**
	 * Set additional data to ajax response.
	 *
	 * @param array $json
	 * @return $this
	 */
	public function setJson(array $json);

	/**
	 * Output Json with special headers.
	 *
	 * @param array $json
	 * @return void
	 */
	public function json(array $json = []);

	/**
	 * @param bool $value
	 * @return $this
	 */
	public function exitAfterRedirect($value);

	/**
	 * @return bool
	 */
	public function isExitAfterRedirect();
}
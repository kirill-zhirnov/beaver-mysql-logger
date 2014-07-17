<?php

namespace KZ\app\interfaces;

/**
 * This is an Abstract Factory for Application. It makes basic components.
 *
 * Interface Kit
 * @package KZ\app\interfaces
 */
interface Kit
{
	/**
	 * Create connectionStorage and fill it with PDO objects.
	 *
	 * @return \KZ\db\interfaces\ConnectionStorage
	 */
	public function makeConnectionStorage();

	/**
	 * Create registry for application.
	 *
	 * @return \KZ\app\interfaces\Registry
	 */
	public function makeRegistry();

	/**
	 * Create controller chain.
	 *
	 * @return \KZ\controller\Chain
	 */
	public function makeControllerChain();


	/**
	 * Create Http request.
	 *
	 * @return \KZ\controller\request\Http
	 */
	public function makeHttpRequest();

	/**
	 * Create controller Kit
	 *
	 * @param array $config
	 * @return \KZ\controller\Kit
	 */
	public function makeControllerKit(array $config);

	/**
	 * Create view.
	 *
	 * @param $templatesPath
	 * @param array $config
	 * @return mixed
	 */
	public function makeView($templatesPath = null, array $config = []);

	/**
	 * Create front controller.
	 *
	 * @param \KZ\Controller\Kit $kit
	 * @param Registry $registry
	 * @return \KZ\controller\Front
	 */
	public function makeFrontController(\KZ\Controller\Kit $kit, Registry $registry);

	/**
	 * Create Observer.
	 *
	 * @return \KZ\event\interfaces\Observer
	 */
	public function makeObserver();

	/**
	 * Create flash messenger object.
	 *
	 * @return \KZ\flashMessenger\interfaces\FlashMessenger
	 */
	public function makeFlashMessenger();

	/**
	 * Create link.
	 *
	 * @param $route
	 * @param array $params
	 * @return \KZ\link\interfaces\Link
	 */
	public function makeLink($route, array $params = []);

	/**
	 * @param \KZ\Controller\interfaces\Request $request
	 * @return \KZ\Controller\interfaces\Response
	 */
	public function makeResponse(\KZ\Controller\interfaces\Request $request);

	/**
	 * @param int $itemCount
	 * @return \KZ\grid\Pager
	 */
	public function makePager($itemCount);
} 
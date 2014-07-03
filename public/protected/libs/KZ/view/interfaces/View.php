<?php

namespace KZ\view\interfaces;
use KZ\app\interfaces as appInterfaces;

/**
 * Interface for View.
 *
 * Interface View
 * @package KZ\view\interfaces
 */
interface View extends \ArrayAccess, \Iterator
{
	/**
	 * @param $templatesPath
	 * @param array $config
	 */
	public function __construct($templatesPath, array $config = []);

	/**
	 * @param View $view
	 * @return $this
	 */
	public function setLayout(View $view);

	/**
	 * @return View
	 */
	public function getLayout();

	/**
	 * Render view file and move request to layout.
	 * If $localPath is null, path will be used from self::setLocalPath().
	 *
	 * @param $localPath
	 * @param array $data
	 * @return $this
	 */
	public function render($localPath = null, array $data = []);

	/**
	 * Set internal local path. See self::render().
	 *
	 * @param $localPath
	 * @return this
	 */
	public function setLocalPath($localPath);

	/**
	 * Render view without moving to layout, only view file.
	 *
	 * @param $localPath
	 * @param array $data
	 * @return View
	 */
	public function renderPartial($localPath, array $data = []);

	/**
	 * @param $path
	 * @return $this
	 */
	public function setTemplatesPath($path);

	/**
	 * @param array $config
	 * @return $this
	 */
	public function setConfig(array $config = []);

	/**
	 * Set variable for template.
	 *
	 * @param $key
	 * @param $val
	 * @return $this
	 */
	public function __set($key, $val);

	/**
	 * Get variable from template.
	 *
	 * @param $key
	 * @return mixed
	 */
	public function __get($key);

	/**
	 * @param $name
	 * @return Helper
	 */
	public function helper($name);

	/**
	 * @return HelperKit
	 */
	public function getHelperKit();

	/**
	 * Set registry to be able pass it in helpers.
	 *
	 * @param appInterfaces\Registry $registry
	 * @return $this
	 */
	public function setRegistry(appInterfaces\Registry $registry);

	/**
	 * @return appInterfaces\Registry
	 */
	public function getRegistry();
}
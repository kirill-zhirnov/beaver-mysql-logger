<?php

namespace KZ\view\interfaces;

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
	 *
	 * @param $path
	 * @param array $data
	 * @return $this
	 */
	public function render($path, array $data = []);

	/**
	 * Render view without moving to layout, only view file.
	 *
	 * @param $path
	 * @param array $data
	 * @param array $config
	 * @return View
	 */
	public function renderPartial($path, array $data = [], array $config = []);

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
	 * To output the result.
	 *
	 * @return string
	 */
	public function __toString();

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
}
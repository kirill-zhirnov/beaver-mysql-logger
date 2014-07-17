<?php

namespace KZ\flashMessenger\interfaces;

interface FlashMessenger extends \Iterator
{
	const SUCCESS = 'success';

	const WARNING = 'warning';

	const ERROR = 'error';

	/**
	 * @param array $config - For example:
	 * <code>
	 * <?php
	 * $config = ['sessionPrefix' => 'myPrefix'];
	 * </code>
	 */
	public function __construct(array $config = []);

	/**
	 * Add message,
	 *
	 * @param $text
	 * @param string $type - Message type.
	 * @return int - Return position for added item.
	 */
	public function add($text, $type = self::SUCCESS);

	/**
	 * Extend messages to next time.
	 *
	 * @return $this
	 */
	public function extend();

	/**
	 * Delete message.
	 *
	 * @param $position
	 * @return $this
	 */
	public function delete($position);

	/**
	 * Get all messages by type.
	 *
	 * @param $type
	 * @return array
	 */
	public function getAllByType($type);

	/**
	 * Set prefix for session.
	 *
	 * @param string $prefix
	 * @return $this
	 */
	public function setSessionPrefix($prefix);

	/**
	 * @return string
	 */
	public function getSessionPrefix();

	/**
	 * @param array $config
	 * @return $this
	 */
	public function setConfig(array $config);
} 
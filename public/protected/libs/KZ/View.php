<?php

namespace KZ;

/**
 * For more details @see \KZ\view\interfaces\View
 *
 * Class View
 * @package KZ
 */
class View extends Registry implements view\interfaces\View
{
	/**
	 * Path to templates
	 *
	 * @var string
	 */
	protected $templatesPath;

	/**
	 * @var view\interfaces\View
	 */
	protected $layout;

	/**
	 * Extension for view files.
	 *
	 * @var string
	 */
	protected $extension = '.php';

	/**
	 * @see Registry::$setDataViaMethods
	 * @var bool
	 */
	protected $setDataViaMethods = false;

	/**
	 * Absolute path to current rendering template.
	 * @var string
	 */
	protected $absolutePath;

	/**
	 * Var name for content in layout. For example:
	 * Template will be rendered and passed as "content" => $result to Layout.
	 *
	 * @var string
	 */
	protected $varNameForContent = 'content';

	/**
	 * Local path to template.
	 * @see self::render()
	 *
	 * @var string
	 */
	protected $localPath;

	public function __construct($templatesPath, array $config = [])
	{
		$this->setTemplatesPath($templatesPath);
		$this->setConfig($config);
	}

	public function setConfig(array $config = [])
	{
		$allowedOptions = ['extension', 'layout', 'varNameForContent', 'localPath'];
		foreach ($allowedOptions as $option) {
			if (!isset($config[$option]))
				continue;

			$method = 'set' . $option;
			if (method_exists($this, $method))
				$this->{$method}($config[$option]);
			else
				$this->{$option} = $config[$option];
		}

		return $this;
	}

	/**
	 * For more details @see \KZ\view\interfaces\View
	 *
	 * @param null $localPath
	 * @param array $data
	 * @return string
	 */
	public function render($localPath = null, array $data = [])
	{
		if (!$localPath)
			$localPath = $this->localPath;

		$content = $this->renderPartial($localPath, $data);

		if ($this->layout)
			return $this->layout->render(null, [
				$this->varNameForContent => $content
			]);

		return $content;
	}

	/**
	 * For more details @see \KZ\view\interfaces\View
	 *
	 * @param $localPath
	 * @param array $data
	 * @return string
	 * @throws \RuntimeException
	 */
	public function renderPartial($localPath, array $data = [])
	{
		$this->assignData($data);
		$this->absolutePath = realpath($this->getAbsoluteTemplatePath($localPath));

		if (!$this->absolutePath)
			throw new \RuntimeException('Cannot open file path: "' . $localPath . '".');

		return $this->renderFile();
	}

	/**
	 * For more details @see \KZ\Registry::offsetSet
	 *
	 * @param mixed|string $offset
	 * @param mixed $value
	 * @return $this|void
	 * @throws \OutOfBoundsException
	 */
	public function offsetSet($offset, $value)
	{
		$denyNames = ['this'];
		if (in_array($offset, $denyNames))
			throw new \OutOfBoundsException('Incorrect key name: "' . $offset . '".');

		return parent::offsetSet($offset, $value);
	}

	/**
	 * Assign data.
	 *
	 * @param array $data
	 * @return $this
	 */
	public function assignData(array $data)
	{
		foreach ($data as $key => $val)
			$this->offsetSet($key, $val);

		return $this;
	}

	/**
	 * Returns assigned data.
	 *
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * If absolute path is passed it will add an extension and return it.
	 *
	 * @param $localPath
	 * @return string
	 */
	public function getAbsoluteTemplatePath($localPath)
	{
		if (preg_match('#^(\/|[a-z]{1}:\\\)#i', $localPath))
			return $localPath . $this->extension;
		else
			return $this->getTemplatesPath() . '/' . $localPath . $this->extension;
	}

	/**
	 * @param $path
	 * @return $this
	 */
	public function setTemplatesPath($path)
	{
		$this->templatesPath = rtrim($path, '/');

		return $this;
	}

	/**
	 * @param view\interfaces\View $layout
	 * @return $this
	 */
	public function setLayout(view\interfaces\View $layout)
	{
		$this->layout = $layout;

		return $this;
	}

	/**
	 * @return view\interfaces\View
	 */
	public function getLayout()
	{
		return $this->layout;
	}

	/**
	 * @return string
	 */
	public function getExtension()
	{
		return $this->extension;
	}

	/**
	 * @return string
	 */
	public function getTemplatesPath()
	{
		return $this->templatesPath;
	}

	/**
	 * @return string
	 */
	public function getVarNameForContent()
	{
		return $this->varNameForContent;
	}

	/**
	 * @param string $localPath
	 * @return $this
	 */
	public function setLocalPath($localPath)
	{
		$this->localPath = $localPath;

		return $this;
	}

	protected function renderFile()
	{
		extract($this->data);

		ob_start();
		require $this->absolutePath;
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
} 
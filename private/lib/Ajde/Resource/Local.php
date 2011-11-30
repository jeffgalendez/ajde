<?php

class Ajde_Resource_Local extends Ajde_Resource
{
	private $_filename;
	
	public function  __construct($type, $base, $action, $format = 'html')
	{
		$this->setBase($base);
		$this->setAction($action);
		$this->setFormat($format);
		parent::__construct($type);
	}

	/**
	 *
	 * @param string $type
	 * @param string $base
	 * @param string $action
	 * @param string $format (optional)
	 * @return Ajde_Resource
	 */
	public static function lazyCreate($type, $base, $action, $format = 'html')
	{
		if (self::getFilenameFromStatic($base, $type, $action, $format)) {
			return new self($type, $base, $action, $format);
		}
		return false;
	}

	/**
	 *
	 * @param string $hash
	 * @return Ajde_Resource
	 */
	public static function fromHash($hash)
	{
		$session = new Ajde_Session('AC.Resource');
		return $session->get($hash);
	}

	public function getBase() {
		return $this->get('base');
	}

	public function getAction() {
		return $this->get('action');
	}

	public function getFormat() {
		return $this->get('format');
	}

	protected static function exist($filename)
	{
		if (is_file($filename)) {
			return true;
		}
		return false;

	}

	protected static function _getFilename($base, $type, $action, $format)
	{
		$filename = false;
		$formatResource = $base . 'res/' . $type . '/' . $action . '.' . $format . '.' . $type;
		if (self::exist($formatResource)) {
			$filename = $formatResource;
		} else {
			$noFormatResource = $base . 'res/' . $type . '/' . $action . '.' . $type;
			if (self::exist($noFormatResource)) {
				$filename = $noFormatResource;
			}
		}
		return $filename;
	}

	public function getFilename()
	{
		if (!isset($this->_filename)) {
			$this->_filename = $this->_getFilename($this->getBase(), $this->getType(), $this->getAction(), $this->getFormat());
		}
		if (!$this->_filename) {
			// TODO:
			throw new Ajde_Exception('Resource could not be found');
		}
		return $this->_filename;
	}

	public static function getFilenameFromStatic($base, $type, $action, $format)
	{
		return self::_getFilename($base, $type, $action, $format);
	}

	protected function getLinkUrl()
	{
		$hash = md5(serialize($this));
		$session = new Ajde_Session('AC.Resource');
		$session->set($hash, $this);
		
		$url = '_core/component:resourceLocal/' . $this->getType() . '/' . $hash . '/';

		if (Config::get('debug') === true)
		{
			$url .= '?file=' . str_replace('%2F', ':', urlencode($this->getFilename()));
		}
		return $url;
	}
}
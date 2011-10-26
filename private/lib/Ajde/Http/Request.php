<?php

class Ajde_Http_Request extends Ajde_Object_Standard
{
	const TYPE_STRING 	= 1;
	const TYPE_HTML 	= 2;
	const TYPE_INTEGER 	= 3;
	const TYPE_FLOAT 	= 4;
	const TYPE_RAW	 	= 5;
	
	const FORM_MIN_TIME	= 2; 	// minimum time to have a post form returned (seconds)
	const FORM_MAX_TIME	= 3600;	// timeout of post forms (seconds) 
	
	/**
	 * @var Ajde_Core_Route
	 */
	protected $_route = null;
	
	/**
	 * @return Ajde_Http_Request
	 */
	public static function fromGlobal()
	{
		$instance = new self();
		if (!empty($_POST) && self::requirePostToken()) {
			
			// Measures against CSRF attacks
			$session = new Ajde_Session('_ajde');
			if (!isset($_POST['_token']) || !$session->has('formTime')) {
				// TODO:
				$exception = new Ajde_Exception('No form token received or no form time set, bailing out to prevent CSRF attack');
				if (Config::getInstance()->debug === true) {
					throw $exception;
				} else {
					Ajde_Exception_Log::logException($exception);	
					Ajde_Http_Response::dieOnCode(403);
				}
			}
			$formToken = $_POST['_token'];
			if (!self::verifyFormToken($formToken) || !self::verifyFormTime($formTime)) {
				// TODO:
				$exception = new Ajde_Exception('No matching form token, or form timed out, bailing out to prevent CSRF attack');
				if (Config::getInstance()->debug === true) {
					throw $exception;
				} else {
					Ajde_Exception_Log::logException($exception);	
					Ajde_Http_Response::dieOnCode(403);
				}
			}
		}
		$global = array_merge($_GET, $_POST);
		foreach($global as $key => $value)
		{
			$instance->set($key, $value);
		}
		return $instance;
	}

	public static function getRefferer()
	{
		return $_SERVER['HTTP_REFERER'];
	}
	
	/**
	 * Security
	 */
	private static function autoEscapeString()
	{
		return Config::getInstance()->autoEscapeString == true;
	}
	
	private static function autoCleanHtml()
	{
		return Config::getInstance()->autoCleanHtml == true;
	}
	
	private static function requirePostToken()
	{
		return Config::getInstance()->requirePostToken == true;
	}
	
	/**
	 * CSRF prevention token
	 */
	public static function getFormToken()
	{
		static $token;
		if (!isset($token)) {
			$token = md5(uniqid(rand(), true));
			$session = new Ajde_Session('_ajde');
			$session->set('formTokenHash', md5($token . $_SERVER['REMOTE_ADDRESS'] . $_SERVER['HTTP_USER_AGENT'] . Config::get('secret')));
		}
		self::markFormTime();
		return $token;
	}
	
	public static function verifyFormToken($requestToken)
	{
		$session = new Ajde_Session('_ajde');
		$sessionTokenHash = $session->get('formTokenHash');
		return (md5($requestToken . $_SERVER['REMOTE_ADDRESS'] . $_SERVER['HTTP_USER_AGENT'] . Config::get('secret')) === $sessionTokenHash);
	}
	
	public static function markFormTime()
	{
		$time = time();
		$session = new Ajde_Session('_ajde');
		$session->set('formTime', $time);
		return $time;
	}
	
	public static function verifyFormTime()
	{
		$session = new Ajde_Session('_ajde');
		$sessionTime = $session->get('formTime');
		if ((time() - $sessionTime) < self::FORM_MIN_TIME ||
			(time() - $sessionTime) > self::FORM_MAX_TIME) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Helpers
	 */
	public function get($key)
	{
		return $this->getParam($key);
	}
	
	public function getParam($key, $default = null, $type = self::TYPE_STRING )
	{
		if ($this->has($key)) {
			switch ($type) {
				case self::TYPE_HTML:
					if ($this->autoCleanHtml() === true) {
						return Ajde_Component_String::clean($this->_data[$key]);
					} else {
						return $this->_data[$key];
					}
					break;
				case self::TYPE_INTEGER:
					return (int) $this->_data[$key];
					break;
				case self::TYPE_FLOAT:
					return (float) $this->_data[$key];
					break;
				case self::TYPE_RAW:
					return $this->_data[$key];
					break;
				case self::TYPE_STRING:
				default:
					if ($this->autoEscapeString() === true) {
						return Ajde_Component_String::escape($this->_data[$key]);
					} else {
						return $this->_data[$key];
					}
			}			
		} else {
			if (isset($default)) {
				return $default;
			} else {
				// TODO:
				throw new Ajde_Exception("Parameter '$key' not present in request and no default value given");
			}
		}
	}
	
	public function getStr($key, $default)	{ return $this->getString	($key, $default); }
	public function getInt($key, $default)	{ return $this->getInteger	($key, $default); }
	
	public function getString($key, $default = null)
	{
		return $this->getParam($key, $default, self::TYPE_STRING);
	}
	
	public function getHtml($key, $default = null)
	{
		return $this->getParam($key, $default, self::TYPE_HTML);
	}
	
	public function getInteger($key, $default = null)
	{
		return $this->getParam($key, $default, self::TYPE_INTEGER);
	}
	
	public function getFloat($key, $default = null)
	{
		return $this->getParam($key, $default, self::TYPE_FLOAT);
	}
	
	/**
	 * @return Ajde_Core_Route
	 */
	public function getRoute()
	{
		if (!isset($this->_route))
		{
			$routeKey = '_route';			
			if (!$this->has($routeKey)) {
				$this->set($routeKey, false);
			}
			$this->_route = new Ajde_Core_Route($this->get($routeKey));
			foreach ($this->_route->values() as $part => $value) {
				$this->set($part, $value);
			}
		}
		return $this->_route;
	}


}
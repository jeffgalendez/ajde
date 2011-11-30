<?php

class Ajde_Session extends Ajde_Object_Standard
{
	protected $_namespace = null;
	
	public function __bootstrap()
	{
		// Session name
		session_name(Config::get('ident') . '_session');
		
		// Cookie parameter
		$lifetime	= 60; // in minutes
		$path		= Config::get('site_path');
		$domain		= Config::get('cookieDomain');
		$secure		= Config::get('cookieSecure');
		$httponly	= Config::get('cookieHttponly');
		session_set_cookie_params($lifetime * 60, $path, $domain, $secure, $httponly);
		session_cache_limiter('private_no_expire');
		
		// Start the session!
		session_start();
		
		// Strengthen session security with REMOTE_ADDR and HTTP_USER_AGENT
		// @see http://shiflett.org/articles/session-hijacking
		if (isset($_SESSION['client']) &&
				$_SESSION['client'] !== md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . Config::get('secret'))) {
			session_regenerate_id();
			session_destroy();
			// TODO:
			$exception = new Ajde_Exception('Possible session hijacking detected. Bailing out.');
			if (Config::getInstance()->debug === true) {
				throw $exception;
			} else {
				Ajde_Exception_Log::logException($exception);	
				Ajde_Http_Response::dieOnCode(403);
			}
		} else {
			$_SESSION['client'] = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . Config::get('secret'));
		}
		
		// remove cache headers invoked by session_start();
		if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
			header_remove('X-Powered-By');
		}
		return true;
	}
	
	public function __construct($namespace = 'default')
	{
		$this->_namespace = $namespace;
	}
	
	public function destroy()
	{
		$_SESSION[$this->_namespace] = null;
		$this->reset(); 
	}
	
	public function setModel($name, $object)
	{
		$this->set($name, serialize($object));	
	}
	
	public function getModel($name)
	{
		return unserialize($this->get($name));
	}
	
	public function has($key)
	{
		if (!isset($this->_data[$key]) && isset($_SESSION[$this->_namespace][$key])) {
			$this->set($key, $_SESSION[$this->_namespace][$key]);
		}
		return parent::has($key);
	}
	
	public function set($key, $value)
	{
		parent::set($key, $value);
		if ($value instanceof AjdeX_Model) {
			// TODO:
			throw new Ajde_Exception('It is not allowed to store a Model directly in the session, use Ajde_Session::setModel() instead.');
		}
		$_SESSION[$this->_namespace][$key] = $value;
	}
	
	public function getOnce($key)
	{
		$return = $this->get($key);
		$this->set($key, null);
		return $return;
	}
}
<?php

class Ajde_Session extends Ajde_Object_Standard
{
	protected $_namespace = null;
	
	public function __bootstrap()
	{
		session_cache_limiter('private_no_expire');
		session_start();
		// remove cache headers invoked by session_start();
		if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
			header_remove('X-Powered-By');
		}
		return true;
	}
	
	public function __construct($namespace = 'default')
	{
		$this->_namespace = $namespace;
		Ajde_Event::register($this, 'beforeHas', array($this, 'onHasParam'));
		Ajde_Event::register($this, 'afterSet', array($this, 'onSetParam'));
	}
	
	public function onHasParam(Ajde_Session $caller, $key)
	{
		if (!isset($caller->_data[$key]) && isset($_SESSION[$caller->_namespace][$key])) {
			$caller->set($key, $_SESSION[$caller->_namespace][$key]);
		}
	}
	
	public function onSetParam(Ajde_Session $caller, $key, $value)
	{
		if ($value instanceof AjdeExtension_Model) {
			// TODO:
			throw new Ajde_Exception('It is not allowed to store a Model in the session, use Model::getValues() instead.');
		}
		$_SESSION[$caller->_namespace][$key] = $value;
	}
}
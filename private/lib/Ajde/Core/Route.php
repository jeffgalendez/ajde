<?php

class Ajde_Core_Route extends Ajde_Object_Standard
{
	protected $_route = null;

	public function __construct($route)
	{
		$this->_route = $route;
		$routeParts = $this->_extractRouteParts();
		$defaultParts = Config::get('defaultRouteParts');
		$parts = array_merge($defaultParts, $routeParts);
		$request = Ajde::app()->getRequest();
		foreach($parts as $part => $value)
		{
			$this->set($part, $value);
			$request->set($part, $value);
		}
	}
	
	public function getModule($default = null) {
		return $this->get("module", $default);
	}

	public function getAction($default = null) {
		return $this->get("action", $default);
	}

	public function getFormat($default = null) {
		return $this->get("format", $default);
	}
	
	protected function _extractRouteParts()
	{
		$matches = array();
		// Emulate these deprecated .htaccess rules
		#RewriteRule ^([^/\.]+)/?$ index.php?module=$1 [QSA,L]
		#RewriteRule ^([^\?/\.]+)/([^\?/\.]+)/?$ index.php?module=$1&action=$2 [QSA,L]
		#RewriteRule ^([^/\.]+)/([^/\.]+)/([^/\.]+)/?$ index.php?module=$1&action=$2&format=$3 [QSA,L]
		#RewriteRule ^([^/\.]+)/([^/\.]+)/([^/\.]+)/([^/\.]+)/?$ index.php?module=$1&action=$2&format=$3&id=$4 [QSA,L]
		
		#RewriteRule ^([^/\.]+)\.([^/\.]+)$ index.php?module=$1&format=$2 [QSA,L]
		#RewriteRule ^([^\?/\.]+)/([^\?/\.]+)\.([^/\.]+)$ index.php?module=$1&action=$2&format=$3 [QSA,L]
		
		$rules = array(
			array('%^([^/\.]+)/?$%' => array('module')),
			array('%^([^\?/\.]+)/([^\?/\.]+)/?$%' => array('module', 'action')),
			array('%^([^/\.]+)/([^/\.]+)/([^/\.]+)/?$%' => array('module', 'action', 'format')),
			array('%^([^/\.]+)/([^/\.]+)/([^/\.]+)/([^/\.]+)/?$%' => array('module', 'action', 'format', 'id')),
			
			array('%^([^/\.]+)\.([^/\.]+)$%' => array('module', 'format')),
			array('%^([^\?/\.]+)/([^\?/\.]+)\.([^/\.]+)$%' => array('module', 'action', 'format')),
		);
		
		foreach($rules as $rule)
		{
			$pattern = key($rule);
			$parts = current($rule);
			if (preg_match($pattern, $this->_route, $matches))
			{
				// removes first element of matches
				array_shift($matches);
				if (count($parts) != count($matches))
				{
					// TODO: exception
					throw new Ajde_Exception("TODO", 90018);	
				} 
				return array_combine($parts, $matches);
			}	
		}
		
	}
}
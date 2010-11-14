<?php

class Ajde_Core_Route extends Ajde_Object_Standard
{
	protected $_route = null;

	public function __construct($route)
	{
		$this->_route = $route;
		$routeParts = $this->_extractRouteParts();
		if (empty($routeParts)) {
			$exception = new Ajde_Exception(sprintf("Invalid route: %s",
					$route), 90021);
			Ajde::routingError($exception);
		}
		$defaultParts = Config::get('defaultRouteParts');
		$parts = array_merge($defaultParts, $routeParts);
		foreach($parts as $part => $value) {
			$this->set($part, $value);
		}
	}
	
	public function __toString()
	{
		return $this->_route = $this->buildRoute();
	}
	
	public function buildRoute()
	{
		$route = $this->getModule() . '/' . $this->getAction() . '/' . $this->getFormat();
		if ($this->has('id')) {
			$route .= '/' . $this->getId();
		}
		return $route;
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
		$rules = array(
			array('%^([^/\.]+)/?$%' => array('module')),
			array('%^([^\?/\.]+)/([0-9]+)/?$%' => array('module', 'id')),
			array('%^([^\?/\.]+)/([^\?/\.]+)/?$%' => array('module', 'action')),			
			array('%^([^/\.]+)/([^/\.]+)/([^/\.]+)/?$%' => array('module', 'action', 'format')),
			array('%^([^/\.]+)/([^/\.]+)/([^/\.]+)/([^/\.]+)/?$%' => array('module', 'action', 'format', 'id')),
			
			array('%^([^/\.]+)\.([^/\.]+)$%' => array('module', 'format')),
			array('%^([^\?/\.]+)/([0-9]+)\.([^/\.]+)$%' => array('module', 'id', 'format')),
			array('%^([^\?/\.]+)/([^\?/\.]+)\.([^/\.]+)$%' => array('module', 'action', 'format')),
			array('%^([^\?/\.]+)/([^\?/\.]+)/([^\?/\.]+)\.([^/\.]+)$%' => array('module', 'action', 'id', 'format')),
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
					throw new Ajde_Exception("Number of routeparts does not match regular expression", 90020);	
				} 
				return array_combine($parts, $matches);
			}	
		}
		
	}
}
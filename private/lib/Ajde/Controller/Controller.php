<?php

class Ajde_Controller extends Ajde_Object_Standard
{
	/**
	 * 
	 * @var Ajde_View
	 */
	protected $_view = null;
		
	public function  __construct($action = null, $format = null)
	{
		$this->setModule(strtolower(str_replace('Controller', '', get_class($this))));
		if (!isset($action) || !isset($format)) {
			$defaultParts = Config::get('defaultRouteParts');
		}
		$this->setAction(isset($action) ? $action : $defaultParts['action']);
		$this->setFormat(isset($format) ? $format : $defaultParts['format']);
	}

	/**
	 *
	 * @param Ajde_Core_Route $route
	 * @return Ajde_Controller
	 */
	public static function fromRoute(Ajde_Core_Route $route)
	{
		$module = $route->getModule();
		$moduleController = ucfirst($module) . 'Controller';
		if (!Ajde_Core_Autoloader::exists($moduleController)) {
			$exception = new Ajde_Exception("Controller for module $module not found",
					90008);
			Ajde::routingError($exception);
		}
		return new $moduleController($route->getAction(), $route->getFormat());
	}

	public function invoke($action = null, $format = null)
	{
		$action = isset($action) ? $action : $this->getAction();
		$format = isset($format) ? $format : $this->getFormat();
		$defaultFunction = $action . "Default";
		$formatFunction = $action . ucfirst($format);
		if (method_exists($this, $formatFunction))
		{
			$actionFunction = $formatFunction;
		}
		elseif (method_exists($this, $defaultFunction))
		{
			$actionFunction = $defaultFunction;
		}
		else
		{
			$exception = new Ajde_Exception(sprintf("Action %s for module %s not found",
						$this->getAction(),
						$this->getModule()
					), 90011);
			Ajde::routingError($exception);
		}
		return $this->$actionFunction();

	}

	/**
	 * 
	 * @return Ajde_View
	 */
	public function getView()
	{
		if (!isset($this->_view)) {
			$this->_view = Ajde_View::fromController($this);
		}
		return $this->_view;
	}
	
	/**
	 * 
	 * @param Ajde_View $view
	 */
	public function setView(Ajde_View $view)
	{
		$this->_view = $view;
	}
	
	/**
	 * Shorthand for $controller->getView()->getContents();
	 */
	public function render()
	{
		return $this->getView()->getContents();
	}
	
	public function loadTemplate()
	{
		throw new Ajde_Core_Exception_Deprecated();
		$view = Ajde_View::fromController($this);
		return $view->getContents();
	}

	public function redirect()
	{
		
	}
}
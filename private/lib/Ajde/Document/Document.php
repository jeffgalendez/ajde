<?php

abstract class Ajde_Document extends Ajde_Object_Standard
{
	public function  __construct()
	{
		
	}
	
	/**
	 *
	 * @param Ajde_Core_Route $route
	 * @return Ajde_Document
	 */
	public static function fromRoute(Ajde_Core_Route $route)
	{
		$format = $route->getFormat();
		$documentClass = "Ajde_Document_Format_" . ucfirst($format);
		if (!Ajde_Core_Autoloader::exists($documentClass)) {
			$exception = new Ajde_Exception("Document format $format not found",
					90009);
			Ajde::routingError($exception);
		}
		return new $documentClass();
	}	

	/**
	 * @return Ajde_Layout
	 */
	public function getLayout()
	{
		return $this->get("layout");
	}

	/**
	 *
	 * @param string $contents
	 */
	public function setBody($contents)
	{
		$this->set('body', $contents);
	}

	/**
	 *
	 * @return string
	 */
	public function getBody()
	{
		return $this->get('body');
	}

	public function render()
	{
		return $this->getLayout()->getContents();
	}

	/**
	 *
	 * @param Ajde_Resource $resource
	 */
	public function addResource(Ajde_Resource $resource) {}

	public function getResourceTypes() {}

}
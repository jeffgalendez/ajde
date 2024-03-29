<?php 

class Ajde_Template_Parser_Phtml_Helper extends Ajde_Object_Standard
{
	/**
	 * 
	 * @var Ajde_Template_Parser
	 */
	protected $_parser = null;
	
	/**
	 * 
	 * @param Ajde_Template_Parser $parser
	 */
	public function __construct(Ajde_Template_Parser $parser)
	{
		$this->_parser = $parser;
	}
	
	/**
	 * 
	 * @return Ajde_Template_Parser
	 */
	public function getParser()
	{
		return $this->_parser;
	}
	
	/************************
	 * Ajde_Component_Js
	 ************************/
	
	/**
	 *
	 * @param string $name
	 * @param string $version
	 * @return void 
	 */
	public function requireJsLibrary($name, $version)
	{
		return Ajde_Component_Js::processStatic($this->getParser(), array('library' => $name, 'version' => $version));
	}
	
	/**
	 * 
	 * @param string $action
	 * @param string $format
	 * @param string $base
	 * @param integer $position
	 * @return void
	 */
	public function requireJs($action, $format = 'html', $base = null, $position = Ajde_Document_Format_Html::RESOURCE_POSITION_DEFAULT)
	{
		return Ajde_Component_Js::processStatic($this->getParser(), array('action' => $action, 'format' => $format, 'base' => $base, 'position' => $position));
	}
	
	/**
	 * 
	 * @param string $action
	 * @param string $format
	 * @param string $base
	 * @return void
	 */
	public function requireJsFirst($action, $format = 'html', $base = null)
	{
		return $this->requireJs($action, $format, $base, Ajde_Document_Format_Html::RESOURCE_POSITION_FIRST);
	}
	
	/**
	 * 
	 * @param string $filename
	 * @param integer $position
	 * @return void
	 */
	public function requireJsPublic($filename, $position = Ajde_Document_Format_Html::RESOURCE_POSITION_DEFAULT)
	{
		return Ajde_Component_Js::processStatic($this->getParser(), array('filename' => $filename, 'position' => $position));
	}
	
	/************************
	 * Ajde_Component_Css
	 ************************/
	
	/**
	 * 
	 * @param string $action
	 * @param string $format
	 * @param string $base
	 * @param integer $position
	 * @return void
	 */
	public function requireCss($action, $format = 'html', $base = null, $position = Ajde_Document_Format_Html::RESOURCE_POSITION_DEFAULT)
	{
		return Ajde_Component_Css::processStatic($this->getParser(), array('action' => $action, 'format' => $format, 'base' => $base, 'position' => $position));
	}

	/**
	 * 
	 * @param string $action
	 * @param string $format
	 * @param string $base
	 * @return void
	 */
	public function requireCssFirst($action, $format = 'html', $base = null)
	{
		return $this->requireCss($action, $format, $base, Ajde_Document_Format_Html::RESOURCE_POSITION_FIRST);
	}
	
	/**
	 * 
	 * @param string $filename
	 * @param integer $position
	 * @return void
	 */
	public function requireCssPublic($filename, $position = Ajde_Document_Format_Html::RESOURCE_POSITION_DEFAULT)
	{
		return Ajde_Component_Css::processStatic($this->getParser(), array('filename' => $filename, 'position' => $position));
	}
	
	/************************
	 * Ajde_Component_Include
	 ************************/

	/**
	 *
	 * @param string $route
	 * @return string
	 */
	public function includeModule($route)
	{
		return Ajde_Component_Include::processStatic($this->getParser(), array('route' => $route));
	}
}
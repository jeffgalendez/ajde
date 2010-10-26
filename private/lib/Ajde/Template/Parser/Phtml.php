<?php

class Ajde_Template_Parser_Phtml extends Ajde_Template_Parser
{
	/**
	 * 
	 * @var Ajde_Template_Parser_Phtml_Helper
	 */
	protected $_helper = null;
	
	/**
	 * 
	 * @return Ajde_Template_Parser_Phtml_Helper
	 */
	public function getHelper()
	{
		if (!isset($this->_helper)) {
			$this->_helper = new Ajde_Template_Parser_Phtml_Helper($this); 
		}
		return $this->_helper;
	}
	
	public function __fallback($method, $arguments)
	{
		$helper = $this->getHelper();
		if (method_exists($helper, $method)) {
			return call_user_func_array(array($helper, $method), $arguments);
		} else {
			throw new Ajde_Exception("Call to undefined method ".get_class($this)."::$method()", 90006);
		}
    }
}
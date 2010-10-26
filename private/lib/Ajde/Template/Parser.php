<?php 

class Ajde_Template_Parser extends Ajde_Object_Standard
{
	/**
	 * 
	 * @var Ajde_Template
	 */
	protected $_template = null;
	
	/**
	 * 
	 * @param Ajde_Template $template
	 */
	public function __construct(Ajde_Template $template)
	{
		$this->_template = $template;
	}
	
	public function __get($name)
	{
		$template = $this->getTemplate();
		if ($template->hasAssigned($name)) {
			return $template->getAssigned($name);
		} else {
			throw new Ajde_Exception("No variable with name '" . $name . "' assigned to template.", 90019);
		}
	}
	
	/**
	 * 
	 * @return Ajde_Template
	 */
	public function getTemplate()
	{
		return $this->_template;
	}
	
	public function parse()
	{
		return $this->_getContents();
	}
	
	protected function _getContents()
	{
		ob_start();
		include $this->getTemplate()->getFilename();
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
}
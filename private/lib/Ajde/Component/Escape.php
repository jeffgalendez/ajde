<?php 

class Ajde_Component_Escape extends Ajde_Component
{
	public static function processStatic(Ajde_Template_Parser $parser, $attributes)
	{
		$instance = new self($parser, $attributes);
		return $instance->process();
	}
	
	protected function _init()
	{
		return array(
			'var' => 'string'
		);
	}
	
	public function process()
	{
		switch($this->_attributeParse()) {
		case 'string':
			$var = $this->attributes['var'];
			$external = Ajde_Core_ExternalLibs::getInstance();
			if ($external->has("HTMLPurifier")) {
				$purifier = $external->get("HTMLPurifier");
				return $purifier->purify($var);
			} else {
				return htmlspecialchars($var);
			}
			break;				
		}		
		// TODO:
		throw new Ajde_Component_Exception();	
	}
}


		
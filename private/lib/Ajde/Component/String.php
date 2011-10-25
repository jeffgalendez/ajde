<?php 

class Ajde_Component_String extends Ajde_Component
{
	protected static $_allowedTags = '<p><ul><li><b><strong><i><em><u>';
	
	public static function processStatic(Ajde_Template_Parser $parser, $attributes)
	{
		$instance = new self($parser, $attributes);
		return $instance->process();
	}
	
	protected function _init()
	{
		return array(
			'escape' 	=> 'escape',
			'clean' 	=> 'clean'
		);
	}
	
	public function process()
	{
		switch($this->_attributeParse()) {
			case 'escape':
				$var = $this->attributes['var'];
				return $this->escape($var);
				break;	
			case 'clean':
				$var = $this->attributes['var'];
				return $this->clean($var);
				break;				
		}		
		// TODO:
		throw new Ajde_Component_Exception();	
	}
	
	public static function escape($var)
	{
		return htmlspecialchars($var);
	}
	
	public static function clean($var)
	{
		$external = Ajde_Core_ExternalLibs::getInstance();
		if ($external->has("HTMLPurifier")) {
			$purifier = $external->get("HTMLPurifier");
			return $purifier->purify($var);
		} else {
			return strip_tags($var, self::$_allowedTags);
		}
	}
}


		
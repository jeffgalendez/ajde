<?php 

class Ajde_Component_String extends Ajde_Component
{
	protected static $_allowedTags = '<table><tr><td><th><a><br><p><div><ul><li><b><h1><h2><h3><h4><h5><h6><strong><i><em><u>';
	
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
		$clean = strip_tags($var, self::$_allowedTags);
		$clean = str_replace('<a ', '<a target=\'_blank\'', $clean);
		return $clean;
	}
	
	public static function purify($var)
	{
		$external = Ajde_Core_ExternalLibs::getInstance();
		if ($external->has("HTMLPurifier")) {
			$purifier = $external->get("HTMLPurifier");
			
			/* @var $purifier HTMLPurifier */
			
			$config = HTMLPurifier_Config::createDefault();
			
			$config->set('AutoFormat.AutoParagraph', true);
			$config->set('AutoFormat.DisplayLinkURI', false);
			$config->set('AutoFormat.Linkify', false);
			$config->set('AutoFormat.RemoveEmpty', true);
			$config->set('AutoFormat.RemoveSpansWithoutAttributes', true);
			
			$config->set('CSS.AllowedProperties', '');
			
			$config->set('HTML.Doctype', 'XHTML 1.0 Strict');
			
			$config->set('URI.DisableExternalResources', true);
			
			$purifier->config = HTMLPurifier_Config::create($config);
			
			return $purifier->purify($var);
		} else {
			return self::clean($var);
		}
	}
}


		
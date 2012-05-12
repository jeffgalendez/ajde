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
	
	public static function escape(&$var, $key = null)
	{
		if (isset($key)) {
			// called from array_walk
			if (is_array($var)) {
				array_walk($var, array("Ajde_Component_String", "escape"));
			} else {
				$var = htmlspecialchars($var, ENT_QUOTES);
			}			
		} else {
			return htmlspecialchars($var, ENT_QUOTES);
		}
	}
	
	public static function clean($var)
	{		
		$clean = strip_tags($var, self::$_allowedTags);
		$clean = str_replace('<a href="http', '<a target=\'_blank\'  href="http', $clean);
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
	
	/**
	* Cut string to n symbols and add delim but do not break words.
	*
	* Example:
	* <code>
	*  $string = 'this sentence is way too long';
	*  echo neat_trim($string, 16);
	* </code>
	*
	* Output: 'this sentence is...'
	*
	* @access public
	* @param string string we are operating with
	* @param integer character count to cut to
	* @param string|NULL delimiter. Default: '...'
	* @return string processed string
	 * 
	 * @see http://www.justin-cook.com/wp/2006/06/27/php-trim-a-string-without-cutting-any-words/
	**/
	public static function trim($str, $n, $delim = '...') {
		$len = strlen($str);
		if ($len > $n) {
			$str = str_replace("\n","",$str);
			$str = str_replace("\r","",$str);
			$n = $n - strlen($delim);
			preg_match('/(.{' . $n . '}.*?)\b/', $str, $matches);
			return rtrim($matches[1]) . $delim;
		} else {
			return $str;
		}
	}
}
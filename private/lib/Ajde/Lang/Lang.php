<?php

class Ajde_Lang extends Ajde_Object_Singleton
{
	protected $_lang;
	
	/**
	 *
	 * @staticvar Ajde_Lang $instance
	 * @return Ajde_Lang
	 */
	public static function getInstance()
	{
		static $instance;
		return $instance === null ? $instance = new self : $instance;
	}
	
	public function __construct()
	{
		$this->setLang($this->detect());
	}
	
	public function getLang()
	{
		return $this->_lang;
	}
	
	public function getShortLang()
	{
		return substr($this->_lang, 0, 2);
	}
	
	public function setLang($lang)
	{
		$this->_lang = $lang;
	}
	 
	public static function _($ident, $module = null)
	{
		return self::getInstance()->get($ident, $module);
	}
	
	public function get($ident, $module = null)
	{
		if (!$module) {	
			foreach(debug_backtrace() as $item) {			
				if (!empty($item['class'])) {
					if (is_subclass_of($item['class'], "Ajde_Controller")) {
						$module = strtolower(str_replace("Controller", "", $item['class']));
						break;
					}
				}
			}
		}
		
		if ($module) {
			$lang = $this->getLang();
			$iniFilename = LANG_DIR . $lang . '/' . $module . '.ini';
			if (file_exists($iniFilename)) {
				$book = parse_ini_file($iniFilename);
				if (array_key_exists($ident, $book)) {
					return $book[$ident];
				}
			}
		}
		return $ident;
	}
	
	protected function detect()
	{
		$defaultLang = Config::get("lang");
		$useLang = $defaultLang;
		if (Config::get("langAutodetect")) {
			$acceptedLangs = $this->getLanguagesFromHeader();
			$availableLangs = $this->getAvailable();
			$availableShortLangs = array();
			foreach($availableLangs as $availableLang) {
				$availableShortLangs[substr($availableLang, 0, 2)] = $availableLang;
			}
			foreach($acceptedLangs as $acceptedLang => $priority) {
				if (in_array($acceptedLang, $availableLangs)) {
					$useLang = $acceptedLang;
					break;
				}
				if (array_key_exists(substr($acceptedLang, 0, 2), $availableShortLangs)) {
					$useLang = $availableShortLangs[substr($acceptedLang, 0, 2)];
					break;
				}
			}
		}
		return $useLang;
	}
	
	protected function getLanguagesFromHeader()
	{
		// @source http://www.thefutureoftheweb.com/blog/use-accept-language-header 
		$langs = false;
		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			// break up string into pieces (languages and q factors)
			preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);
			if (count($lang_parse[1])) {
    			// create a list like "en" => 0.8
				$langs = array_combine($lang_parse[1], $lang_parse[4]);
				
				// set default to 1 for any without q factor
				foreach ($langs as $lang => $val) {
					if ($val === '') $langs[$lang] = 1;
				}
				
				// sort list based on value	
        		arsort($langs, SORT_NUMERIC);
			}
		}
		return $langs;
	}
	
	protected function getAvailable()
	{
		$langs = Ajde_FS_Find::findFiles(LANG_DIR, '*');
		$return = array();
		foreach($langs as $lang) {
			$return[] = basename($lang);
		}
		return $return;
	}
}
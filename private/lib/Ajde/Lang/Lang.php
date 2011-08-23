<?php

class Ajde_Lang extends Ajde_Object_Static
{
	public static function _($ident, $module = null)
	{
		return self::get($ident, $module);
	}
	
	public static function get($ident, $module = null)
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
			$lang = Config::get("lang");
			$iniFilename = LANG_DIR . $lang . '/' . $module . '.ini';
			if (file_exists($iniFilename)) {
				$book = parse_ini_file($iniFilename);
				if (array_key_exists($ident, $book)) {
					return $book[$ident];
				}
			}
		}
		return false;
	}
}

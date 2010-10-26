<?php

class AjdeExtension extends Ajde_Object_Standard
{
	private static $_extensions = array();
	
	public static function load($extension)
	{
		if (!array_key_exists($extension, self::$_extensions)) {
			$className = 'AjdeExtension_' . ucfirst($extension);
			self::$_extensions[$extension] = new $className();
		}	
		return self::$_extensions[$extension];
	}
}

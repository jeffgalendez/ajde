<?php

class Ajde_Core_Autoloader
{
	public static function register() {
		// Configure autoloading
		spl_autoload_register(array("Ajde_Core_Autoloader", "autoload"));
	}

	public static function autoload($className)
	{
	    // Add libraries and config to include path
		$dirs = array(
			PRIVATE_DIR.CLASS_DIR,
			PRIVATE_DIR.CONFIG_DIR
		);

		$files = array();

		// Namespace/Class.php naming
		$files[] = str_ireplace('_', '/', $className) . ".php";

		// Namespace_Class.php naming
		$files[] = $className . ".php";

		// Namespace/Class/Class.php naming
		$classNameArray = explode("_", $className);
		$tail = end($classNameArray);
		$head = implode("/", $classNameArray);
		$files[] = $head . "/" . $tail . ".php";

		foreach ($dirs as $dir)
		{
			foreach (array_unique($files) as $file)
			{
				$path = $dir.$file;
				if (file_exists($path)) {
					include_once($path);
					return;
				}
			}

		}

		/*
		 * Throwing exceptions is only possible as of PHP 5.3.0
		 * See: http://php.net/manual/en/language.oop5.autoload.php
		 */
		if (version_compare(PHP_VERSION, '5.3.0') >= 0)
		{
			throw new Ajde_Core_Autoloader_Exception("Unable to load $className", 90005);
		}
	}
}
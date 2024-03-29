<?php

class Ajde_Core_Autoloader
{
	public static function register()
	{
		// Configure autoloading
		spl_autoload_register(array("Ajde_Core_Autoloader", "autoload"));
	}
	
	public static function getIncompatibleClasses()
	{
		// These (ZF) classes could pose problems to the Ajde MVC system
		return array(
			'Zend_Application',
			'Zend_Loader_Autoloader',
			'Zend_Application_Bootstrap_Bootstrap'
		);	
	} 

	public static function autoload($className)
	{
		if (in_array($className, self::getIncompatibleClasses())) {
			throw new Ajde_Exception('Could not create instance of incompatible class ' . $className . '.', 90018);
		}
		
	    // Add libraries and config to include path
		$dirs = array(
			PRIVATE_DIR.LIB_DIR,
			PRIVATE_DIR.APP_DIR.CONFIG_DIR,
			PRIVATE_DIR.APP_DIR.MODULE_DIR
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

		// controller
		$files[] = strtolower($className) . "/" . strtolower($className) . "Controller.php";

		foreach ($dirs as $dir)
		{
			foreach (array_unique($files) as $file)
			{
				$path = $dir.$file;
				if (file_exists($path)) {
					if (class_exists('Ajde_Cache'))
					{
						Ajde_Cache::getInstance()->addFile($path);
					}
					include_once $path;
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

	public static function exists($className)
	{
		try
		{
			// Pre PHP 5.3.0
			if (!class_exists($className)) {
				return false;
			}
		}
		catch (Ajde_Exception $exception)
		{
			// 90005: Unable to load CLASSNAME
			if ($exception->getCode() === 90005)
			{
				return false;
			}
			else
			{
				throw $exception;
			}
		}
		return true;
	}
}
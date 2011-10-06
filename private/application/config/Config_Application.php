<?php

class Config_Application extends Config_Default
{	
	// Site parameters
	public $ident				= "samples";
	public $sitename 			= "Ajde Samples";	
	public $version 			= array(
									"number" => "0.1",
									"name" => "alpha"
									);
									
									
	//public $homepageRoute;
	//public $defaultRouteParts;
	//public $aliases;
	public $routes				= array(
		// ./module/post-5 
		array('%^([^\?/\.]+)/post-([0-9]+)/?$%' => array('module', 'id'))
	);
	
	//public $lang;
	//public $langAutodetect;
	//public $langAdapter;
	//public $timezone;
	//public $layout;
	
	//public $compressResources;
	//public $debug;
	//public $useCache;
	//public $documentProcessors;
	
	//public $dbAdapter;
	public $dbDsn				= array(
									"host" 		=> "localhost",
									"dbname"	=> "ajde-samples"
									);
	public $dbUser 				= "ajde-samples";
	public $dbPassword 			= "blog";	
	//public $registerNamespaces;
	
	//public $bootstrap;
	
}
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
	public $responseCodeRoute	= array(
									'404' => 'main/code404.html',
									'401' => 'user/logon.html'
									);
	
	//public $autoEscapeString;
	//public $autoCleanHtml;
	//public $requirePostToken;
	public $secret				= '0de9edf642ecc58511cfb76ba751f82f59e45917';
	
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
	public $dbPassword 			= "ajde-samples";	
	//public $registerNamespaces;
	
	public $bootstrap			= array(									
									"Ajde_Exception_Handler",
									"Ajde_Session",
									"Ajde_Core_ExternalLibs",
									"AjdeX_User_Autologon",
									);
	
}
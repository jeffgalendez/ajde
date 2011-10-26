<?php

class Config_Application extends Config_Default
{	
	// Site parameters
	public $ident				= "project";
	public $sitename 			= "Project Name";	
	public $version 			= array(
									"number" => "0.1",
									"name" => "alpha"
									);
									
									
	//public $homepageRoute;
	//public $defaultRouteParts;
	//public $aliases;
	//public $routes;
	
	//public $lang;
	//public $langAutodetect;
	//public $langAdapter;
	//public $timezone;
	//public $layout;
	//public $responseCodeRoute;
	
	//public $autoEscapeString;
	//public $autoCleanHtml;
	//public $requirePostToken;
	public $secret				= 'randomstring';
	//public $cookieDomain;
	//public $cookieSecure;
	//public $cookieHttponly;
	
	//public $compressResources;
	//public $debug;
	//public $useCache;
	//public $documentProcessors;
	
	//public $dbAdapter;
	//public $dbDsn;	
	//public $dbUser;
	//public $dbPassword;	
	//public $registerNamespaces;
	
	//public $bootstrap;
	
}
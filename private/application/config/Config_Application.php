<?php

class Config_Application extends Config_Default
{	
	// Site parameters
	public $ident				= "unst";
	public $sitename 			= ".UNST";	
	public $version 			= array(
									"number" => "1",
									"name" => "alpha"
									);
									
									
	//public $homepageRoute;
	//public $defaultRouteParts;
	//public $aliases;
	//public $routes;
	
	public $lang 				= "nl_NL";
	public $langAutodetect		= false;
	public $langAdapter			= 'gettext';
	//public $timezone;
	public $layout				= 'unst';
	
	//public $compressResources;
	//public $debug;
	//public $useCache;
	//public $documentProcessors;
	
	//public $dbAdapter;
	public $dbDsn				= array(
									"host" 		=> "localhost",
									"dbname"	=> "unst"
									);
	
	public $dbUser				= 'unst';
	public $dbPassword			= 'unst';	
	public $registerNamespaces	= array(
									'Unst'
									);
	
	//public $bootstrap;
	
}
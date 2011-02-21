<?php

class Config_Base
{	
	// Site parameters
	public $ident				= "project";
	public $sitename 			= "Project Name";	
	public $version 			= array(
									"number" => "0",
									"name" => "alpha"
									);
	public $homepageRoute		= "static.html";
	public $defaultRouteParts	= array(
									"module" => "welcome",
									"action" => "view",
									"format" => "html"
									);       
									
	public $routes				= array(
									);
	public $lang 				= "en";
	public $timezone			= "Europe/Amsterdam";
	public $layout 				= "default";
	
	// Performance
	public $compressResources	= true;
	public $debug 				= false;
	public $useCache			= true;
	public $documentProcessors	= array();
	
	// Extension settings
	public $dbAdapter			= "mysql";
	public $dbDsn				= array(
									"host" 		=> "localhost",
									"dbname"	=> "ajde"
									);
	public $dbUser 				= "ajde";
	public $dbPassword 			= "ajde";	
	public $registerNamespaces	= array(
									);

	// Which modules should we call on bootstrapping?
	public $bootstrap			= array(
									"Ajde_Exception_Handler",
									"Ajde_Session",
									);

	function __construct()
	{
		$this->local_root = $_SERVER["DOCUMENT_ROOT"].str_replace("/index.php", "", $_SERVER["PHP_SELF"]);
		$this->site_domain = $_SERVER["SERVER_NAME"];
		$this->site_path = str_replace('index.php', '', $_SERVER["PHP_SELF"]);
		$this->site_root = $this->site_domain . $this->site_path;
		date_default_timezone_set($this->timezone);
	}
	
}
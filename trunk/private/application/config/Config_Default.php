<?php

class Config_Default
{
	/**
	 * Please do not edit this configuration file, this makes it easier
	 * to upgrade when defaults are changed or new values are introduced.
	 * Instead, use Config_Application to override default values. 
	 */
		
	// Site parameters, defined in Config_Application
	public $ident				= null;
	public $sitename 			= null;	
	public $description			= null;	
	public $author				= null;
	public $version 			= array(
									"number" => null,
									"name" => null
									);
									
	// Routing
	public $homepageRoute		= "home.html";
	public $defaultRouteParts	= array(
									"module" => "main",
									"controller" => null,
									"action" => "view",
									"format" => "html"
									);       
	public $aliases				= array(
									"home.html" => "main.html"
									);											
	public $routes				= array(
									);
									
	// Presentation
	public $titleFormat			= '%2$s - %1$s'; // %1$s is project title, %2$s is document title
	public $lang 				= "en_GB";
	public $langAutodetect		= true;
	public $langAdapter			= "ini";
	public $timezone			= "Europe/Amsterdam"; // "UTC" for Greenwich Mean Time
	public $layout 				= "default";
	public $responseCodeRoute	= array(
									'404' => 'main/code404.html'
									);			
	
	// Security
	public $autoEscapeString	= true;
	public $autoCleanHtml		= true;
	public $requirePostToken	= true;
	public $secret				= 'randomstring';
	public $cookieDomain		= false;
	public $cookieSecure		= false;
	public $cookieHttponly		= true;
	
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
	public $registerNamespaces	= array();
	public $overrideClass		= array();
	
	/**
	 * Use this text editor for CRUD operations 
	 * 
	 * @var string (aloha|jwysiwyg|ckeditor) 
	 */
	public $textEditor			= 'ckeditor';


	// Which modules should we call on bootstrapping?
	public $bootstrap			= array(									
									"Ajde_Exception_Handler",
									"Ajde_Session",
									"Ajde_Core_ExternalLibs",
									"Ajde_User_Autologon"
									);

	function __construct()
	{
		$this->local_root = $_SERVER["DOCUMENT_ROOT"] . str_replace("/index.php", "", $_SERVER["PHP_SELF"]);
		$this->site_domain = $_SERVER["SERVER_NAME"];
		$this->site_path = str_replace('index.php', '', $_SERVER["PHP_SELF"]);
		$this->site_root = $this->site_domain . $this->site_path;
		$this->lang_root = $this->site_root;
		date_default_timezone_set($this->timezone);
	}
	
}
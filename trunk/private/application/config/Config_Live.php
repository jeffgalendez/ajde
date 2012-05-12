<?php

class Config_Live extends Config_Application {

	// Performance
	public $compressResources	= true;
	public $debug 				= false;
	public $useCache			= true;
	public $documentProcessors	= array(
									"css"	=> array("Less"),
									"html"	=> array("Compressor")
								  );	
	function __construct() {
		parent::__construct();
	}
	
}
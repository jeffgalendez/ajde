<?php

class Config_Dev extends Config_Base {
	
	// Performance
	public $compressResources	= false;
	public $debug 				= true;
	public $useCache			= false;
	public $documentProcessors	= array(
									"html" => array(
										"Compressor",
										"Beautifier"
									)
								  );	

	function __construct() {
		parent::__construct();
	}
	
}
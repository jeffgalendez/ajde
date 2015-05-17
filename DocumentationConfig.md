# Documentation Config #

More information about configuration settings in Ajde.


# Details #

Ajde provides configuration settings for several development stages. All configuration settings are saved in PHP classes in the `private/config` directory.

Your application configuration (`Config_Application`) inherits the default values from `Config_Default` and can be extended to suit your needs for various development stages. The default Ajde installation comes with pre-defined configuration files for the development stage (`Config_Dev`) and production stage (`Config_Live`).

# Examples #

## Getting configuration parameters ##

If we want to refer to our configuration parameters, we call `Config::getInstance()`. This returns an instance of the current configuration object depending on the selected stage, which is defined in `Config::$stage`. If only a single parameter is needed, you can call `Config::get($param)` statically.

```
// Multiple parameters
$config = Config::getInstance();
printf("Current stage: %s<br/>", Config::$stage);
printf("Project name: %s<br/>", $config->sitename);
printf("Project language: %s<br/>", $config->lang);

// Single parameter
printf("Project identifier: %s<br/>", Config::get("ident"));
```

## Defining configuration parameters ##

If we want to define different configurations for - let's say - our database in the live and development stage, edit the `Config_Live.php` and `Config_Dev.php` in the `private/config` directory to include this parameters. Example:

```
<?php

class Config_Live extends Config_Application {
	
	/* Other parameters... */

	// Database settings for live environment
	public $dbDsn = array("host" => "localhost", "dbname" => "db");
	public $dbUser = "username";
	public $dbPassword = "password";

	function __construct() {
		parent::__construct();
	}
	
}
```

Now, when the `Config::$stage` setting changes between **dev** and **live**, the database parameters retrieved via the `Config` class will change accordingly.
<?php

// Check PHP version
if (version_compare(PHP_VERSION, '5.2.3') < 0) {
	die('<h3>Ajde requires PHP/5.2.3 or higher.<br>You are currently running PHP/'.phpversion().'.</h3><p>You should contact your host to see if they can upgrade your version of PHP.</p>');
}

// Show errors before errorhandler is initialized in bootstrapping
error_reporting(E_ALL);

// Uncomment to hide uncatchable fatal errors
//ini_set('display_errors', 0);

// Try to catch fatal errors
function shutdown()
{
	$traceOn = array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR);
	if ($error = error_get_last()) if (in_array($error['type'], $traceOn))
	{
		$error = new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
		echo Ajde_Exception_Handler::trace($error);
	}
}
register_shutdown_function('shutdown');

// The only thing missing in PHP < 5.3
function issetor(&$what, $else)
{
	return isset($what) ? $what : $else;
}

// Define paths
define('PRIVATE_DIR', 		'private/');
define('PUBLIC_DIR', 		'public/');
define('TEMPLATE_DIR', 		'template/');
define('APP_DIR', 			PRIVATE_DIR.'application/');
define('LIB_DIR', 			PRIVATE_DIR.'lib/');
define('VAR_DIR', 			PRIVATE_DIR.'var/');
define('CACHE_DIR', 		VAR_DIR.'cache/');
define('CONFIG_DIR', 		APP_DIR.'config/');
define('LAYOUT_DIR', 		APP_DIR.'layout/');
define('LOG_DIR', 			VAR_DIR.'log/');
define('MODULE_DIR', 		APP_DIR.'modules/');

// Configure the autoloader
require_once(LIB_DIR."Ajde/Core/Autoloader.php");
Ajde_Core_Autoloader::register();

// Run the main application
$app = Ajde::create();

try {
	$app->run();	
} catch (Ajde_Core_Exception_Deprecated $e) {
	// Throw $e to die on deprecated functions / methods (only in debug mode)
	throw $e;
}
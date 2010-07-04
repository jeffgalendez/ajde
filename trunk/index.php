<?php

// Check PHP version
if (version_compare(PHP_VERSION, '5.2.3') < 0) {
	die('<h3>Ajde requires PHP/5.2.3 or higher.<br>You are currently running PHP/'.phpversion().'.</h3><p>You should contact your host to see if they can upgrade your version of PHP.</p>');
}

// Show errors before errorhandler is initialized in bootstrapping
error_reporting(E_ALL);
//ini_set('display_errors', 0);

// Catch fatal errors
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

// Now, where can we find the files?
define('PRIVATE_DIR', 'private/');
define('PUBLIC_DIR', 'public/');
define('LIB_DIR', 'lib/');
define('CONFIG_DIR', 'config/');
define('APP_DIR', 'app/');
define('LAYOUT_DIR', 'layout/');

// Configure the autoloader
require_once(PRIVATE_DIR.LIB_DIR."Ajde/Core/Autoloader.php");
Ajde_Core_Autoloader::register();

// Run the Ajde framework application
$app = Ajde::create();
$app->run();
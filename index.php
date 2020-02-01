<?php
	$application_environment = 'DEV';

	define('APPS_PATH', dirname(__FILE__));	
	define('SITE_ENV', $application_environment);

	if(SITE_ENV=="DEV"){
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}else{
		ini_set('display_errors', 0);
		ini_set('display_startup_errors', 0);
		error_reporting(0);
	}
	require_once("./core/common.class.php");
?>

	                        

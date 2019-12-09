<?php
	//echo get_current_user();die();
	define('APPS_PATH', dirname(__FILE__));
	require_once("./application/config/config.php");

	if(SITE_ENV=="DEV"){
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}else{
		ini_set('display_errors', 0);
		ini_set('display_startup_errors', 0);
		error_reporting(0);
	}

	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	$queryString = explode('/?',str_replace(APPS_URL, '', $actual_link));
	$splitUrl = explode('/',str_replace(APPS_URL, '', $queryString[0]));

	if($splitUrl[0]=='index.php'){
		$getCtrl = explode('.',str_replace(APPS_URL, '', $splitUrl[1]));
		$ctrlName = strtolower($getCtrl[0]);
		$ctrlFunc = $splitUrl[2];			
	}else{
		$getCtrl = explode('.',str_replace(APPS_URL, '', $splitUrl[0]));
		$ctrlName = strtolower($getCtrl[0]);
		$ctrlFunc = !empty($splitUrl[1]) ? $splitUrl[1] : '';
	}

	if($ctrlName==''){
		$ctrlName = default_controller;
	}
	//echo './application/controller/'.$ctrlName.'Controller.class.php';
		
	if($ctrlName=='logout'){
		require_once('./application/controller/loginController.class.php');
		$controller = new loginController();
		$controller->logout();
	}else{
		$dynCtrl = $ctrlName.'Controller';	
		
		if(file_exists('./application/controller/'.$ctrlName.'Controller.class.php')){
			
			require_once('./application/controller/'.$ctrlName.'Controller.class.php');
			$controller = new $dynCtrl();

			if(empty($ctrlFunc)){
				$ctrlFuncName = 'index';
			}else{
				$ctrlFuncName = $ctrlFunc;
			}
			if(method_exists($controller,$ctrlFuncName)){
				$controller->{$ctrlFuncName}();			
			}else{
				header('HTTP/1.1 404 Not Found'); 
				include APPS_PATH.'/application/views/404.php';
				die;
			}
		}else{			
			header('HTTP/1.1 404 Not Found'); 
			include APPS_PATH.'/application/views/404.php';
			die;
		}
	}


?>

	                        
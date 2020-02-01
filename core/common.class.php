<?php

	require_once(APPS_PATH."/".APPLICATION_FOLDER."/config/config.php");
	require_once(APPS_PATH."/".APPLICATION_FOLDER."/config/constant.php");

	function autoloadfile($classname) {
		$filename = APPS_PATH.'/'.CORE_FOLDER.'/'.$classname.'.class.php';
		include_once($filename);
	}
	spl_autoload_register("autoloadfile");

	function init(){
		if(file_exists(APPS_PATH.'/'.CORE_FOLDER.'/loader.class.php')){
			$obj = new Loader();
			return $obj;
		}
	}

	function pr($data, $exit=false){
		if($exit){
			echo '<pre>';
			print_r($data);
			echo '</pre>';
			exit;
		}else{
			echo '<pre>';
			print_r($data);
			echo '</pre>';
		}
	}
	include_once(APPS_PATH."/application/config/session.php");

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
		$ctrlName = $config['default_page'];
	}
		
	if($ctrlName=='logout'){
		require_once('./'.APPLICATION_FOLDER.'/controller/loginController.class.php');
		$controller = new loginController();
		$controller->logout();
	}else{
		$dynCtrl = $ctrlName.'Controller';	
		
		if(file_exists('./'.APPLICATION_FOLDER.'/controller/'.$ctrlName.'Controller.class.php')){
			
			require_once('./'.APPLICATION_FOLDER.'/controller/'.$ctrlName.'Controller.class.php');
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
				include APPS_PATH.'/'.APPLICATION_FOLDER.'/'.$config['404_page'].'.php';
				die;
			}
		}else{			
			header('HTTP/1.1 404 Not Found'); 
			include APPS_PATH.'/'.APPLICATION_FOLDER.'/'.$config['404_page'].'.php';
			die;
		}
	}

?>

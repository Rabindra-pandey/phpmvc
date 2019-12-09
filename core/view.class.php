<?php

require_once('./controller/commonController.class.php');

class view{

	private $controller;
	private $date;
	
	function __construct(){	

		$this->controller = new commonController();

		/*$this->data = $this->controller->{$getView[0]};
		require_once("../views/$view.php");*/

		/*$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$getView = explode('.',str_replace(APPS_URL, '', $actual_link));*/

		/*if(isset($getView[0]) && $getView[0]==''){
			$data = $controller->{$getView[0]};
			include_once('../views/index.php');

		}else{
			$res = $controller->{$getView[0]};
			include_once('../views/'.$getView[0].'.'.$getView[1]);
		}*/
	}

	public static function cview($loadview, $data){
		include_once(APPS_PATH."/views/".$loadview.".php");
		echo '<pre>';
	print_r($data);exit;
		return $data;
	}
}


?>
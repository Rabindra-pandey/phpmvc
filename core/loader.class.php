<?php

	
class Loader{
	public function __construct(){	
	}	

	public function model($name){
		if(file_exists(APPS_PATH.'/'.APPLICATION_FOLDER.'/model/'.$name.'.class.php')){
			include APPS_PATH.'/'.APPLICATION_FOLDER.'/model/'.$name.'.class.php';
			$obj = new $name();
			return $obj;
		}
	}
}


?>
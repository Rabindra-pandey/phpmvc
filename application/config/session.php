<?php
session_start();

if(!empty($_SESSION['emp_id'])){
	if(!empty($_GET['action'])){
		$action = $_GET['action'];
		if($action=="logout"){			
			unset($_SESSION['role']);
			unset($_SESSION['emp_name']);
			unset($_SESSION['emp_id']);
			session_destroy();
			die;
		}
	}
}

function form_token($length = 10) {
	$token = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	//$_SESSION['token'] = $token;
	//return $token;
}


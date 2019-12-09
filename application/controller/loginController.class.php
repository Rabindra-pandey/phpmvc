<?php

require_once(APPS_PATH.'/application/model/homeModel.class.php');
require_once(APPS_PATH.'/core/controller.class.php');

class loginController extends Controller{

	private $model;
	
	function __construct(){	
		//parent::__construct();
		$this->model = new homeModel();		
	}

	function index(){
		//echo 'gskdcpm'.md5('gskdcpm').'<br>';
		//echo 'gskdcad'.md5('gskdcad');exit;		
		$getSess = $this->isLoggedIn('userdata');
		if(!empty($getSess)){
			unset($_SESSION['userdata']);		
		}
		$data = [];
		if(!empty($this->queryString(1))){
			$getVal = $this->queryString(1);
			$data['querystring']['key'] = $getVal['key'];
			$data['querystring']['val'] = $getVal['val'];
		}
		$this->view('login/index', $data, false);
	}

	function loginuser(){		
		$getSess = $this->isLoggedIn('userdata');
		if(!empty($getSess)){
			unset($_SESSION['userdata']);		
		}
		//$this->pr($_POST, true);
		if(isset($_POST['userlogin'])){
			$empid = $_POST['empid'];
			$password = $_POST['password'];
			$userType = $_POST['user_type'];
			
			if(!empty($userType) && $userType=='qa'){				
				$sql = "SELECT `emp_name`, `empid`, `role`, `email` FROM `users` WHERE `empid`='$empid' and `password`='".sha1($password)."' AND `role_user`='Quality Analyst'";
				$getRow = $this->model->selectSingleRecordFromPKT($sql);
				if(!empty($getRow)){
					$userData['username'] = $getRow['emp_name'];
					$userData['empid'] = $getRow['empid'];
					$userData['email'] = $getRow['email'];
					$userData['role'] = $getRow['role'];

					$_SESSION['userdata'] = $userData;
					header('Location: '.APPS_URL.'qaticket'); exit;
				}else{
					header('Location: '.APPS_URL.'login/index/?error=Please enter correct details'); exit;
				}
			}else{
				$sql = "SELECT `emp_name`, `empid`, `email`, `role`, `flag` FROM `users` WHERE (`empid`='$empid' OR `email`='$empid') and `password`='".md5($password)."'";
				$getRow = $this->model->selectSingleRecordFromCalculator($sql);
				//$this->pr($getRow, true);
				if($getRow && $getRow['flag']=='calc_table'){
					$userData['username'] = $getRow['emp_name'];
					$userData['empid'] = $getRow['empid'];
					$userData['email'] = $getRow['email'];
					$userData['role'] = $getRow['role'];
					
					$_SESSION['userdata'] = $userData;
					
					if($getRow['role']==1){
						header('Location: '.APPS_URL.'home'); exit;
					}else if($getRow['role']==2){
						header('Location: '.APPS_URL.'eticket/dashboard'); exit;
					}else{
						header('Location: '.APPS_URL.'login/index/?error=Please enter correct user type'); exit;
					}					
				}else{
					header('Location: '.APPS_URL.'login/index/?error=Please enter correct details'); exit;
				}
			}
			
			
		}else{
			header('Location: '.APPS_URL.'login/index/?error=Please enter user credential'); exit;
		}
	}
	
	function logout(){
		if(!empty($_SESSION['userdata'])){
			unset($_SESSION['userdata']);	
		}
		header('Location: '.APPS_URL.'login'); exit;
	}
}


?>
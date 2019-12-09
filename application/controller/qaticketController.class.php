<?php

require_once(APPS_PATH.'/application/model/homeModel.class.php');
require_once(APPS_PATH.'/core/controller.class.php');


class qaticketController extends Controller{

	private $model;
	private $getSess;
	
	function __construct(){	
		$this->model = new homeModel();
		
		$this->getSess = $this->isLoggedIn('userdata');
		if(empty($this->getSess)){
			header('Location: '.APPS_URL.'login'); exit;
		}else if(!empty($this->getSess) && $this->getSess['role']!=3){
			header('Location: '.APPS_URL.'login'); exit;
		}
	}

	function index(){
		if(!empty($this->queryString(1))){
			$getVal = $this->queryString(1);
			$data['querystring']['key'] = $getVal['key'];
			$data['querystring']['val'] = $getVal['val'];
		}
		
		$empId = $this->getSess['empid'];		
		$sql = "SELECT `id`, `ticket_no`,`country`,`region`,`channel`,`draft_no`,`complexity`,`job_received_date`,`job_status`,`annotated_pages`,`qc_page_count`,`designer_name`,`qa_name`,`job_delivery_date` FROM `ticket_information` WHERE (`job_status`='Open' OR `job_status`='Recheck' OR `job_status`='WIP') AND `qa_id`=$empId AND `status`='A' ORDER BY `job_delivery_date` ASC";
		$getRows = $this->model->selectAllRecordsFromCalculator($sql);
		$data['lists'] = $getRows;
		//$this->pr($data['lists'], true);
		$this->view('qaticket/index', $data);
	}
	
	function changestatus(){
		$hiddenstatus = $_POST['hiddenstatus'];
		$idx = $_POST['idx'];
		$jobstatus = $_POST['jobstatus'];	
		$comments = $_POST['comments'];	
		$getRow = false;
		$todaydate = date('Y-m-d H:i:s');
		if($hiddenstatus!=$jobstatus){
			if($jobstatus=='Not Approved'){
				$insSql = "INSERT INTO `tbl_job_status` SET `job_status`='$jobstatus', `submit_date`='$todaydate'";
				$lastInsertedId = $this->model->insertIntoCalculator($insSql);
				
				if($lastInsertedId){
					$sql = "UPDATE `ticket_information` SET `job_status`='$jobstatus', `qc_completion_date`='$todaydate', `comments`='$comments', `job_status_id`=$lastInsertedId WHERE `id`=$idx";
					$getRow = $this->model->updateIntoCalculator($sql);
				}
			}else if($jobstatus=='Approved'){
				$sql = "UPDATE `ticket_information` SET `job_status`='$jobstatus', `comments`='$comments', `qc_completion_date`='$todaydate' WHERE `id`=$idx";
				$getRow = $this->model->updateIntoCalculator($sql);	
			}else{
				$sql = "UPDATE `ticket_information` SET `job_status`='$jobstatus', `comments`='$comments' WHERE `id`=$idx";
				$getRow = $this->model->updateIntoCalculator($sql);	
			}
			
							
			if($getRow){
				echo true;
			}else{
				echo false;
			}
		}else{
			echo false;
		}
	}
}


?>
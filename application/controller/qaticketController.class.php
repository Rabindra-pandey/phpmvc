<?php

class qaticketController extends Controller{

	private $model;
	private $getSess;
	
	function __construct(){	
		parent::__construct();
		$this->model = $this->load->model('homeModel');
		
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
		$sql = "SELECT `id`, `ticket_no`,`country`,`region`,`channel`,`draft_no`,`complexity`,`job_received_date`,`job_status`,`annotated_pages`,`qc_page_count`,`designer_name`,`qa_name`,`job_delivery_date` FROM `ticket_information` WHERE (`job_status`='' OR `job_status`='Open' OR `job_status`='Recheck' OR `job_status`='WIP') AND `qa_id`=$empId AND `status`='A' ORDER BY `job_delivery_date` ASC";
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

	function qaedit(){		
		$qaId = $this->getSess['empid'];
		$editId = $this->url_segments(3);
		
		$data['regions'] = $this->getRegion();
		$data['channels'] = $this->getChannel();
		$data['designerList'] = $this->getDesignerName();		
		if(is_numeric($editId)){
			$sql = "SELECT * FROM `ticket_information` WHERE `id`=$editId AND `qa_id`=$qaId AND `status`='A'";
			$data['edit'] = $this->model->selectSingleRecordFromCalculator($sql);
			//$this->pr($data['edit'], true);
			if(!empty($data['edit'])){			
				$this->view('qaticket/qaedit', $data);
			}else{
				header('Location: '.APPS_URL.'qaticket/?error=Something is wrong. Please try again.'); exit;
			}
		}else{
			header('Location: '.APPS_URL.'qaticket/?error=Something is wrong. Please try again.'); exit;
		}
	}
	
	function saveticket(){
		if(isset($_POST['submit_eticket'])){
			$setVal = '';
			$editedId = $_POST['editedId'];			
			
			if(!empty($_POST['eticket'])){
				foreach($_POST['eticket'] as $key=>$val){
					if($val!='' && $editedId!=''){
						if($key=='job_received_date' || $key=='job_delivery_date'){
						}else{
							$setVal .= "$key='$val', ";
						}
					}
				}
			}	
			$finalStr = substr($setVal, 0, -2);
			//$this->pr($finalStr, true);
			if(!empty($setVal) && !empty($editedId)){
				$selSql = "UPDATE `ticket_information` SET $finalStr WHERE `id`=$editedId";
				$getStatus = $this->model->updateIntoCalculator($selSql);
				if($getStatus){	
					header('Location: '.APPS_URL.'qaticket/index/?succ=Data has been saved successfully'); exit;
				}else{
					header('Location: '.APPS_URL.'qaticket/index/?error=Something is wrong. Please try again.'); exit;
				}
			}
		}else{
			header('Location: '.APPS_URL.'qaticket'); exit;		
		}
	}
	
	function getRegion(){
		$selSql = "SELECT DISTINCT `team` AS region FROM `employee` WHERE `team`!='ViiV' AND `team`!='' ORDER BY `team` ASC";
		$getRows = $this->model->selectAllRecordsFromPLI($selSql);
		return $getRows;
	}
	
	function getChannel(){
		$selSql = "SELECT DISTINCT `channel` FROM `employee` WHERE `channel`!='admin' and `channel`!='' ORDER BY `channel` ASC";
		$getRows = $this->model->selectAllRecordsFromPLI($selSql);
		return $getRows;
	}
	
	function getDesignerName(){
		$selSql = "SELECT `u`.`employee_id`, `u`.`first_name`, `u`.`last_name`, `u`.`team` FROM `employee` as u ORDER BY `u`.`first_name` ASC";
		$getRows = $this->model->selectAllRecordsFromPLI($selSql);
		return $getRows;
	}
}


?>
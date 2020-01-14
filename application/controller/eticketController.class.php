<?php

class eticketController extends Controller{

	private $model;
	
	function __construct(){	
		parent::__construct();
		$this->model = $this->load->model('homeModel');
		
		$getSess = $this->isLoggedIn('userdata');
		if(empty($getSess)){
			header('Location: '.APPS_URL.'login'); exit;
		}else if(!empty($getSess) && $getSess['role']!=2){
			header('Location: '.APPS_URL.'login'); exit;
		}
	}

	function index(){

		$data['regions'] = $this->getRegion();
		$data['channels'] = $this->getChannel();
		$data['designerList'] = $this->getDesignerName();
		
		if(!empty($this->queryString(1))){
			$getVal = $this->queryString(1);
			$data['querystring']['key'] = $getVal['key'];
			$data['querystring']['val'] = $getVal['val'];
		}
		//$this->pr($data['designerList'], true);
		$this->view('eticket/index', $data);
	}

	function dashboard(){
		
		$data['regions'] = $this->getRegion();
		$data['channelCounts'] = $this->channelWiseDataCount();
		$data['resAllocVsAsgTkt'] = $this->resourceAllocVsAssignedTkt();
		$regChannCounts = $this->RegionChannelWiseDataCount();
		
		$regChanCount = [];
		if(count($regChannCounts)>0){
			foreach($data['regions'] as $key=>$val){
				foreach($regChannCounts as $rcval){
					if($val['region']==$rcval['region']){
						$regChanCount[$rcval['region']][$rcval['channel']] = $rcval['channelcount'];
					}
				}
			}
		}
		$data['regChannCounts'] = $regChanCount;
		//$this->pr($regChanCount, true);
		$this->view('eticket/dashboard', $data);
	}
	
	function channelWiseDataCount(){		
		$sql = "SELECT count(`channel`) AS channelcount,`channel`  FROM `ticket_information` WHERE (`job_status`='Open' OR `job_status`='WIP' OR `job_status`='Recheck') AND `status`='A' GROUP BY `channel`";
		$rows = $this->model->selectAllRecordsFromCalculator($sql);
		if($rows){
			return $rows;
		}
		return false;
	}
	
	function RegionChannelWiseDataCount(){		
		$sql = "SELECT `region`, count(`channel`) AS channelcount, `channel`  FROM `ticket_information` WHERE (`job_status`='Open' OR `job_status`='WIP' OR `job_status`='Recheck') AND `status`='A' GROUP BY `region`, `channel` ORDER BY `region` ASC";
		$rows = $this->model->selectAllRecordsFromCalculator($sql);
		if($rows){
			return $rows;
		}
		return false;
	}
	
	function resourceAllocVsAssignedTkt(){		
		$sql = "SELECT count(`ra`.`resource_allocation_id`) as tktfrmra FROM `resource_allocation` AS ra WHERE `ra`.`allocation_status`='completed' AND `ra`.`flag_active`=1 AND `ra`.`pli_calc_flag`='CM'";		
		$row1 = $this->model->selectAllRecordsFromPLI($sql);
		
		$sql2 = "SELECT count(`id`) as opentkt FROM `ticket_information` WHERE (`job_status`='Open' OR `job_status`='WIP' OR `job_status`='Recheck') AND `status`='A'";
		$row2 = $this->model->selectAllRecordsFromCalculator($sql2);
		$data['resource_allocation_ticket'] = $row1[0]['tktfrmra'];
		$data['assigned_ticket'] = $row2[0]['opentkt'];
		
		return $data;
	}

	function edit(){
		$editId = $this->url_segments(3);
		$resourceAllocId = $this->url_segments(4);
		
		$data['regions'] = $this->getRegion();
		$data['channels'] = $this->getChannel();
		$data['designerList'] = $this->getDesignerName();		
		if(is_numeric($editId)){
			$sql = "SELECT * FROM `ticket_information` WHERE `id`=$editId AND `status`='A'";
			$data['edit'] = $this->model->selectSingleRecordFromCalculator($sql);
			//$this->pr($data['edit'], true);
			if(!empty($data['edit'])){			
				$this->view('eticket/index', $data);
			}else{
				header('Location: '.APPS_URL.'eticket/?error=Something is wrong. Please try again.'); exit;
			}
		}else if($editId=='newticketentry' && $resourceAllocId!=''){
			$sql = "SELECT `ra`.`employee_id` AS designer_id,`ra`.`eform_ticket` AS ticket_no,`ra`.`end_date` AS job_received_date, CONCAT_WS(' ', `em`.`first_name`, `em`.`last_name`) AS designer_name,`em`.`channel`,`em`.`team` AS region FROM `resource_allocation` AS ra LEFT JOIN `employee` AS em ON `ra`.`employee_id`=`em`.`employee_id` WHERE `ra`.`resource_allocation_id`=$resourceAllocId";
		
			$data['edit'] = $this->model->selectSingleRecordFromPLI($sql);
			$data['resource_alloc_id'] = $resourceAllocId;
			
			if(!empty($data['edit'])){			
				$this->view('eticket/index', $data);
			}
		}else if($editId=='rejectedticket' && $resourceAllocId!=''){
			$sql = "SELECT * FROM `ticket_information` WHERE `id`=$resourceAllocId AND `status`='A'";
			$data['edit'] = $this->model->selectSingleRecordFromCalculator($sql);
		
			$data['rejected_id'] = $resourceAllocId;	
			if(!empty($data['edit'])){			
				$this->view('eticket/index', $data);
			}
		}else if($editId=='approvedticket' && $resourceAllocId!=''){
			$sql = "SELECT * FROM `ticket_information` WHERE `id`=$resourceAllocId AND `status`='A'";
			$data['edit'] = $this->model->selectSingleRecordFromCalculator($sql);
		
			$data['readyonly'] = 'readonly';	
			if(!empty($data['edit'])){			
				$this->view('eticket/index', $data);
			}
		}
	}

	function delete(){
		$deletedId = $this->url_segments(3);
		
		$sql = "UPDATE `ticket_information` SET `status`='C' WHERE `id`=$deletedId";
		$affectedRow = $this->model->updateIntoCalculator($sql);
		if($affectedRow){			
			header('Location: '.APPS_URL.'eticket/ticketlist/?succ=Ticket has been deleted successfully.'); exit;
		}else{
			header('Location: '.APPS_URL.'eticket/ticketlist/?error=Something is wrong. Please try again.'); exit;
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
	
	function saveticket(){
		if(isset($_POST['submit_eticket'])){
			$setVal = '';
			$editedId = $_POST['editedId'];
			$resourceallocid = $_POST['resourceallocid'];
			$rejectedId = $_POST['rejectedId'];
			$recheck = null;
			if(!empty($_POST['eticket'])){
				foreach($_POST['eticket'] as $key=>$val){
					if($key=='job_status' && $val=='Recheck'){
						$recheck = $val;
					}
					if($val!='' && $editedId==''){
						if($key=='job_received_date' || $key=='job_delivery_date'){
							$setVal .= "$key='".date('Y-m-d H:i:s', strtotime($val))."', ";
						}else{
							$setVal .= "$key='$val', ";
						}
					}else if($editedId!=''){
						if($key=='job_received_date' || $key=='job_delivery_date'){
							$setVal .= "$key='".date('Y-m-d H:i:s', strtotime($val))."', ";
						}else{
							$setVal .= "$key='$val', ";
						}
					}
				}
				if(!empty($rejectedId)){
					$selSql = "UPDATE `ticket_information` SET `is_reassigned`='Y' WHERE `id`=$rejectedId";
					$this->model->updateIntoCalculator($selSql);
				}
			}	
			$finalStr = substr($setVal, 0, -2);
			if(!empty($setVal)){
				if(empty($editedId)){
					if($recheck=='Recheck' || $recheck=='Not Approved'){
						$todaydate = date('Y-m-d H:i:s');
						$insSql = "INSERT INTO `tbl_job_status` SET `job_status`='$recheck', `submit_date`='$todaydate'";
						$insertedId = $this->model->insertIntoCalculator($insSql);
						if($insertedId){
							$finalStr .= ", job_status_id=$insertedId";
						}
					}
					$selSql = "INSERT INTO `ticket_information` SET $finalStr"; //INNER JOIN `role_user` as ru ON cast(ru.user_id AS Integer)=u.id  WHERE `ru`.`role_id`=1
					$lastInsertedId = $this->model->insertIntoCalculator($selSql);
					if($lastInsertedId){
						if(empty($rejectedId)){
							$raql = "UPDATE `resource_allocation` SET pli_calc_flag='QA' WHERE `resource_allocation_id`=$resourceallocid";
							$this->model->updateIntoPLI($raql);
						}						
						header('Location: '.APPS_URL.'eticket/?succ=Data has been saved successfully'); exit;
					}else{
						header('Location: '.APPS_URL.'eticket/?error=Data is not saved. Please cantact to dev team.'); exit;						
					}
				}else if(!empty($editedId)){ 
					$selSql = "UPDATE `ticket_information` SET $finalStr WHERE `id`=$editedId";
					$getStatus = $this->model->updateIntoCalculator($selSql);
					if($getStatus){	
						header('Location: '.APPS_URL.'eticket/ticketlist/?succ=Data has been updated successfully'); exit;
					}else{
						header('Location: '.APPS_URL.'eticket/ticketlist/?error=Something is wrong. Please try again.'); exit;
					}
				}
			}
		}else{
			header('Location: '.APPS_URL.'eticket'); exit;		
		}
	}

	function ticketlist(){		
		if(!empty($_POST['startdate']) && !empty($_POST['enddate'])){
			$startDate = date('Y-m-d 00:00:00', strtotime($_POST['startdate']));
			$endDate = date('Y-m-d 00:00:00', strtotime($_POST['enddate']));		
		}else{
			$startDate = date('Y-m-d 00:00:00', strtotime('-30 days'));
			$endDate = date('Y-m-d H:i:s');
		}
		
		if(!empty($this->queryString(1))){
			$getVal = $this->queryString(1);
			$data['querystring']['key'] = $getVal['key'];
			$data['querystring']['val'] = $getVal['val'];
		}
		
		$selSql = "SELECT `id`, `ticket_no`,`country`,`region`,`channel`,`draft_no`,`complexity`,`job_received_date`,`job_status`,`annotated_pages`,`qc_page_count`,`designer_name`,`qa_name`,`job_delivery_date` FROM `ticket_information` WHERE (`job_status`='' OR `job_status`='Open' OR `job_status`='Recheck' OR `job_status`='WIP') AND `status`='A' ORDER BY `job_received_date` DESC"; // AND (`job_received_date`>='$startDate' AND `job_received_date`<='$endDate')  || BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
		$getRows = $this->model->selectAllRecordsFromCalculator($selSql);
		$data['lists'] = $getRows;		
		//$this->pr($data['lists'], true);
		$this->view('eticket/ticketlist', $data);
	}
	
	function checkingTicketStatus(){
		$ticketno = $_POST['ticketno'];
		$draftno = $_POST['draftno'];
		$jobstatus = $_POST['jobstatus'];
		
		echo $sql = "SELECT `job_status`, `draft_no` FROM `ticket_information` WHERE `ticket_no`='$ticketno' AND `draft_no`='$draftno' AND `job_status`='$jobstatus' AND `status`='A'"; return false;
		$getRow = $this->model->selectSingleRecordFromCalculator($sql);
		if($getRow){
			echo json_encode($getRow);
		}else{
			echo false;
		}
	}

	function ticketfromdesigner(){			
		if(!empty($this->queryString(1))){
			$getVal = $this->queryString(1);
			$data['querystring']['key'] = $getVal['key'];
			$data['querystring']['val'] = $getVal['val'];
		}
		
		$sql = "SELECT `ra`.`resource_allocation_id`,`ra`.`employee_id`,`ra`.`eform_ticket`,`ra`.`end_date`,CONCAT_WS(' ', `em`.`first_name`, `em`.`last_name`) AS emp_name,`em`.`channel`,`em`.`team` AS region FROM `resource_allocation` AS ra LEFT JOIN `employee` AS em ON `ra`.`employee_id`=`em`.`employee_id` WHERE `ra`.`allocation_status`='completed' AND `ra`.`flag_active`=1 AND `ra`.`pli_calc_flag`='CM' ORDER BY `ra`.`end_date` DESC";
		
		$data['lists'] = $this->model->selectAllRecordsFromPLI($sql);
		//$this->pr($data['lists'], true);		
		$this->view('eticket/ticketfromdesigner', $data);
	}

	function newticketentry(){		
		$rejectedId = $this->url_segments(3);
		
		if(!empty($rejectedId)){
			$sql = "UPDATE `resource_allocation` SET `pli_calc_flag`='QR' WHERE `resource_allocation_id`=$rejectedId";
			$getStatus = $this->model->updateIntoPLI($sql);
			if($getStatus){
				header('Location: '.APPS_URL.'eticket/ticketfromdesigner/?succ=Resource allocation ticket has been deleted successfully from QC'); exit;		
			}else{
				header('Location: '.APPS_URL.'eticket/ticketfromdesigner/?error=Something is wrong. Please try again'); exit;
			}
		}else{
			header('Location: '.APPS_URL.'eticket/ticketfromdesigner/?error=Please select correct row'); exit;
		}
	}

	function qareject(){		
		$rejectedId = $this->url_segments(3);
		
		if(!empty($rejectedId)){
			$sql = "UPDATE `resource_allocation` SET `pli_calc_flag`='QR' WHERE `resource_allocation_id`=$rejectedId";
			$getStatus = $this->model->updateIntoPLI($sql);
			if($getStatus){
				header('Location: '.APPS_URL.'eticket/ticketfromdesigner/?succ=Resource allocation ticket has been deleted successfully from QC'); exit;		
			}else{
				header('Location: '.APPS_URL.'eticket/ticketfromdesigner/?error=Something is wrong. Please try again'); exit;
			}
		}else{
			header('Location: '.APPS_URL.'eticket/ticketfromdesigner/?error=Please select correct row'); exit;
		}
	}

	function approvedticket(){	
		if(!empty($_POST['startdate']) && !empty($_POST['enddate'])){
			$startDate = date('Y-m-d 00:00:00', strtotime($_POST['startdate']));
			$endDate = date('Y-m-d 23:59:59', strtotime($_POST['enddate']));		
		}else{
			$startDate = date('Y-m-d 00:00:00', strtotime('-30 days'));
			$endDate = date('Y-m-d 23:59:59');
		}
		
		if(!empty($this->queryString(1))){
			$getVal = $this->queryString(1);
			$data['querystring']['key'] = $getVal['key'];
			$data['querystring']['val'] = $getVal['val'];
		}
		
		$selSql = "SELECT `ti`.`id`, `ti`.`ticket_no`,`ti`.`country`,`ti`.`region`,`ti`.`channel`,`ti`.`draft_no`,`ti`.`complexity`,`ti`.`job_received_date`,`ti`.`job_delivery_date`,`ti`.`job_status`,`ti`.`annotated_pages`,`ti`.`designer_name`,`ti`.`qa_name`,`ti`.`is_escalated`,`js`.`job_status` AS job_status_tbl_status FROM `ticket_information` AS ti LEFT JOIN `tbl_job_status` AS js ON `ti`.`job_status_id`=`js`.`id` WHERE `ti`.`job_status`='Approved' AND (`ti`.`qc_completion_date`>='$startDate' AND `ti`.`qc_completion_date`<='$endDate') AND `ti`.`status`='A' ORDER BY `ti`.`qc_completion_date` DESC";
		$getRows = $this->model->selectAllRecordsFromCalculator($selSql);
		$data['lists'] = $getRows;		
		$this->view('eticket/approvedticket', $data);
	}

	function rejectedticket(){				
		$selSql = "SELECT `ti`.`id`, `ti`.`ticket_no`,`ti`.`country`,`ti`.`region`,`ti`.`channel`,`ti`.`draft_no`,`ti`.`complexity`,`ti`.`job_received_date`,`ti`.`job_delivery_date`,`ti`.`job_status`,`ti`.`annotated_pages`,`ti`.`designer_name`,`ti`.`qa_name`,`ti`.`comments`,`ti`.`is_escalated`,`js`.`job_status` AS job_status_tbl_status FROM `ticket_information` AS ti LEFT JOIN `tbl_job_status` AS js ON `ti`.`job_status_id`=`js`.`id` WHERE `ti`.`job_status`='Not Approved' AND `ti`.`is_reassigned`='N' AND `ti`.`status`='A' ORDER BY `ti`.`qc_completion_date` DESC";
		
		$getRows = $this->model->selectAllRecordsFromCalculator($selSql);
		$data['lists'] = $getRows;		
		$this->view('eticket/rejectedticket', $data);
	}
	
	function checkingyicketinfo(){
		$ticketNo = $_POST['ticketno'];
		
		$selSql = "SELECT `id` FROM `ticket_information` WHERE `ticket_no` LIKE '$ticketNo%' AND `status`='A'";		
		$getRows = $this->model->selectAllRecordsFromCalculator($selSql);
		if($getRows){
			echo 1;
		}else{
			echo 0;
		}
	}
}


?>
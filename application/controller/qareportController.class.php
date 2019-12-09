<?php

require_once(APPS_PATH.'/application/model/homeModel.class.php');
require_once(APPS_PATH.'/core/controller.class.php');
		
require(APPS_PATH.'/application/third_party/spreadsheet/vendor/autoload.php');
//include the classes needed to create and write .xlsx file
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class qareportController extends Controller{

	private $model;
	
	function __construct(){	
		$this->model = new homeModel();
		
		$getSess = $this->isLoggedIn('userdata');
		if(empty($getSess)){
			header('Location: '.APPS_URL.'login'); exit;
		}else if(!empty($getSess) && $getSess['role']!=2){
			header('Location: '.APPS_URL.'login'); exit;
		}
	}
	
	function index(){
		$getVal = $this->queryStringMulti();
		$getVal2 = $this->queryStringMulti(1);
		$data['querystring'] = [];
		if(!empty($getVal)){
			$data['querystring'][array_keys($getVal)[0]] = $getVal[array_keys($getVal)[0]];
		}
		if(!empty($getVal2)){
			$data['querystring'][array_keys($getVal2)[0]] = $getVal2[array_keys($getVal2)[0]];
		}
		//$this->pr($data['querystring'], true);
		$data['regions'] = $this->getRegion();
		$data['channels'] = $this->getChannel();		
		
		$this->view('qareport/index', $data);
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
	
	function reportupload(){				
		if(isset($_FILES['uploadfile'])){
			$fileTOArr = explode('.',$_FILES['uploadfile']['name']);
			$fileExt = strtolower(end($fileTOArr));
			
			if($fileExt=='xlsx'){
				$inputFileType = 'Xlsx';
				$inputTempName = $_FILES['uploadfile']['tmp_name'];
				$fileName = $_FILES['uploadfile']['name'];
				$targetPath = APPS_PATH."/assets/uploaded_files/".$fileName;
				if(move_uploaded_file($inputTempName, $targetPath)){
					$inputFileName = $targetPath;
					
					$spreadsheet = new Spreadsheet();
					
					$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($inputFileName);
					$reader->setReadDataOnly(true);
					$worksheetData = $reader->listWorksheetInfo($inputFileName);				
					
					$sheetName = $worksheetData[0]['worksheetName'];
					$reader->setLoadSheetsOnly($sheetName);
					$spreadsheet = $reader->load($inputFileName);

					$worksheet = $spreadsheet->getActiveSheet();
					$readDataArr = $worksheet->toArray();
					
					
					$tableHeaderArr = ['ticket_no','region','country','channel','job_type','draft_no','new_amends','job_comes_from','complexity','job_received_date','job_status','total_pages','annotated_pages','qc_page_count','designer_name','qa_name','qc_completion_date','comments','suggestions','quality_codes','status','job_status_tbl_status','id'];	
					
					$excelHeaderArr = ['Ticket No.','Region','Country','Channel','Job Type','Draft','New Amends/DC Miss','Hub/LOC','Complexity','Job Received Date','Status','Total Pages','Annotated Pages','QC Page Count','Designer Name','QA Name','QA Completion Date','Comments','Suggestions','Quality Code','Active or Cancel','Current Status','DB Table Id'];	
					
					foreach($readDataArr[0] as $key => $value){
						if(trim($value)==trim($excelHeaderArr[$key])){
						}else{
							header('Location: '.APPS_URL.'qareport/?error=Header is not matched with uploaded file. Please check the excel header.'); 
							unlink($targetPath);
							exit;
						}
					}
					
					$finalArr = [];
					$idx = 0;
					foreach($readDataArr as $key => $value){
						if($key>0){
							foreach($value as $excelKy=>$excelVal){
								$finalArr[$idx][$tableHeaderArr[$excelKy]] = $excelVal;
							}	
							$idx++;						
						}
					}					
					
					$getConstStatus = JOB_STATUS;
					function capitalizeArr($item){
						return strtoupper($item);
					}
					$getStatusData = array_map('capitalizeArr', $getConstStatus);
					
					if(!empty($finalArr)){
						$errorReport = '<br>';
						$succReport = '<br>';
						foreach($finalArr as $ky=>$val){
							$updateStringIntoTable = ''; 
							$checkStatus = ''; 
							foreach($val as $actKey=>$actVal){
								if(!empty($actVal)){
									if($actKey=='job_status_tbl_status' or $actKey=='id'){
									}else{
										$statusVal = $val['job_status'];
										if(in_array(strtoupper($statusVal), $getStatusData)){
											$getStatusFrmTbl = $this->getDataFromInfoTbl($val['id']);
											if(strtolower($getStatusFrmTbl[0]['job_status'])==strtolower($statusVal) && $getStatusFrmTbl[0]['job_status']!='Not Approved'){
												$updateStringIntoTable .= "$actKey='$actVal', job_status_id=null, ";
											}else{
												$updateStringIntoTable .= "$actKey='$actVal', ";
											}
										}else{
											$errorReport .= "Status is not matched with table data. Please check and enter the right value in status column.<br>";
											$checkStatus = $val['id'];
											break;
										}
									}
								}
							}
							if($checkStatus!=''){
								$errorReport .= "Data with id $checkStatus is not updated due to mismatch of status value. Please check the status and upload again.<br>";
							}
							if($updateStringIntoTable!=''){
								$finalStringIntoTable = substr($updateStringIntoTable, 0, -2);
								$selSql = "UPDATE `ticket_information` SET $finalStringIntoTable WHERE `id`=".$val['id'];
								$getStatus = $this->model->updateIntoCalculator($selSql);
								if($getStatus){
									$succReport .= "Data with id ".$val['id']." is updated successfully.<br>";
								}else{
									$errorReport .= str_replace("=", ":", $finalStringIntoTable). '----> IS NOT UPDATED IN DATABSE TABLE. PLEASE CONNECT TO DEV TEAM WITH THIS STRING.<br>';
								}
							}else{
								header('Location: '.APPS_URL.'qareport/?error='.substr($errorReport, 0, -4));  
								unlink($targetPath);
								exit;
							}
						}	
						//if($errorReport=='<br>'){
							header('Location: '.APPS_URL.'qareport/?succ='.substr($succReport, 0, -4).'&error='.substr($errorReport, 0, -4));  
							unlink($targetPath);
							exit;
						/*}else{
							header('Location: '.APPS_URL.'qareport/?error='.substr($errorReport, 0, -4)); exit;
						}*/						
					}else{
						header('Location: '.APPS_URL.'qareport/?error=Something is wrong. Please connect to dev team.');  
						unlink($targetPath);
						exit;
					}					
				}
			}else{
				header('Location: '.APPS_URL.'qareport/?error=Please upload xlsx extension file'); exit;
			}
		}
	}
	
	function getDataFromInfoTbl($id){
		$selSql = "SELECT job_status FROM `ticket_information` WHERE id=$id";
		return $this->model->selectAllRecordsFromCalculator($selSql);
	}
	
	function getTicketInformationData(){
		$selSql = "SELECT ti.* FROM `ticket_information` AS ti WHERE (`ti`.`job_status`='Not Approved' OR `ti`.`job_status`='Approved') AND `ti`.`status`='A' ORDER BY `ti`.`job_received_date` ASC";
		$ticketinfo = $this->model->selectAllRecordsFromCalculator($selSql);
		
		if(!empty($ticketinfo)){
			return $ticketinfo;
		}else{
			return false;
		}
	}
	
	function generatereport(){
		$where = '';
		if(!empty($_POST['startdate']) && !empty($_POST['enddate'])){
			$startDate = date('Y-m-d 00:00:00', strtotime($_POST['startdate']));
			$endDate = date('Y-m-d 23:59:59', strtotime($_POST['enddate']));		
		}else{
			$startDate = date('Y-m-d 00:00:00', strtotime('-30 days'));
			$endDate = date('Y-m-d 23:59:59');
		}
		$regn = $_POST['region'];
		$chnn = $_POST['channel'];
		if(!empty($regn)){
			$where .= "`region`='$regn' AND ";
		}
		if(!empty($chnn)){
			$where .= "`channel`='$chnn' AND ";
		}
		
		$selSql = "SELECT ti.*, `js`.`job_status` AS `job_status_tbl_status` FROM `ticket_information` AS ti LEFT JOIN `tbl_job_status` AS js ON `ti`.`job_status_id`=`js`.`id` WHERE $where (`ti`.`job_status`='Not Approved' OR `ti`.`job_status`='Approved') AND (`ti`.`qc_completion_date`>='$startDate' AND `ti`.`qc_completion_date`<='$endDate') AND `ti`.`status`='A' ORDER BY `ti`.`job_received_date` ASC";
		$ticketinfo = $this->model->selectAllRecordsFromCalculator($selSql);
		
		$spreadsheet = new Spreadsheet();
		
		/*$tableHeaderArr = ['ticket_no','region','country','channel','job_type','draft_no','new_amends','job_comes_from','complexity','job_received_date','job_status','total_pages','annotated_pages','qc_page_count','designer_name','qa_name','qc_completion_date','comments','suggestions','quality_codes','status','job_status_tbl_status','id'];	
		
		$excelHeaderArr = ['Ticket No.','Region','Country','Channel','Job Type','Draft','New Amends/DC Miss','Hub/LOC','Complexity','Job Received Date','Status','Total Pages','Annotated Pages','QC Page Count','Designer Name','QA Name','QA Completion Date','Comments','Suggestions','Quality Code','Active or Cancel','Current Status','DB Table Id'];	
		*/
		
		/**
		*
		* If Anthings are changes in the below list. Please change the array in above, in proper place, commented "$tableHeaderArr" & "$excelHeaderArr" array and update to all other place for consistancy.
		*
		*/
		
		$spreadsheet->setActiveSheetIndex(0)
						 ->setCellValue('A1', 'Ticket No.')
						 ->setCellValue('B1', 'Region')
						 ->setCellValue('C1', 'Country')
						 ->setCellValue('D1', 'Channel')
						 ->setCellValue('E1', 'Job Type')
						 ->setCellValue('F1', 'Draft')
						 ->setCellValue('G1', 'New Amends/DC Miss')
						 ->setCellValue('H1', 'Hub/LOC')
						 ->setCellValue('I1', 'Complexity')
						 ->setCellValue('J1', 'Job Received Date')
						 ->setCellValue('K1', 'Status')
						 ->setCellValue('L1', 'Total Pages')
						 ->setCellValue('M1', 'Annotated Pages')
						 ->setCellValue('N1', 'QC Page Count')
						 ->setCellValue('O1', 'Designer Name')
						 ->setCellValue('P1', 'QA Name')
						 ->setCellValue('Q1', 'QA Completion Date')
						 ->setCellValue('R1', 'Comments')
						 ->setCellValue('S1', 'Suggestions')
						 ->setCellValue('T1', 'Quality Code')
						 ->setCellValue('U1', 'Active or Cancel')
						 ->setCellValue('V1', 'Current Status')
						 ->setCellValue('W1', 'DB Table Id');
		
		$n = 2;
		if(!empty($ticketinfo)){
			foreach ($ticketinfo as $row){ 
				$status = $row['job_status_tbl_status']=='Recheck' ? 'Recheck' : $row['job_status'];
				$spreadsheet->setActiveSheetIndex(0)
								 ->setCellValue('A'.$n, $row['ticket_no'])
								 ->setCellValue('B'.$n, $row['region'])
								 ->setCellValue('C'.$n, $row['country'])
								 ->setCellValue('D'.$n, $row['channel'])
								 ->setCellValue('E'.$n, $row['job_type'])
								 ->setCellValue('F'.$n, $row['draft_no'])
								 ->setCellValue('G'.$n, $row['new_amends'])
								 ->setCellValue('H'.$n, $row['job_comes_from'])
								 ->setCellValue('I'.$n, $row['complexity'])
								 ->setCellValue('J'.$n, $row['job_received_date'])
								 ->setCellValue('K'.$n, $status)
								 ->setCellValue('L'.$n, $row['total_pages'])
								 ->setCellValue('M'.$n, $row['annotated_pages'])
								 ->setCellValue('N'.$n, $row['qc_page_count'])
								 ->setCellValue('O'.$n, trim($row['designer_name']))
								 ->setCellValue('P'.$n, trim($row['qa_name']))
								 ->setCellValue('Q'.$n, $row['qc_completion_date'])
								 ->setCellValue('R'.$n, $row['comments'])
								 ->setCellValue('S'.$n, $row['suggestions'])
								 ->setCellValue('T'.$n, $row['quality_codes'])
								 ->setCellValue('U'.$n, $row['status'])
								 ->setCellValue('V'.$n, $row['job_status_tbl_status'])
								 ->setCellValue('W'.$n, $row['id']);
				$n++;
			}
		}
		
		//set style for 1st Row
		$cell_st =[
					 'font' =>['bold' => true],
					 'alignment' =>['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
					 'borders'=>['bottom' =>['style'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM]]
					];
		
		$spreadsheet->getActiveSheet()->getStyle('A1:W1')->applyFromArray($cell_st);


		$spreadsheet->getActiveSheet()->setTitle('QA Report');

		//make object of the Xlsx class to save the excel file
		$writer = new Xlsx($spreadsheet);
		$fxls = 'QA-Report-'.date('d-m-Y-H_i_s').'.xlsx';

		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
		// redirect output to client browser
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename='.$fxls);
		header('Cache-Control: max-age=0');
		
		ob_end_clean();
		$writer->save('php://output');
		exit();
	}
}


?>
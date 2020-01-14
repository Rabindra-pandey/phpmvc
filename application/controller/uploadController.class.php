<?php

require_once(APPS_PATH.'/application/third_party/spreadsheet/vendor/autoload.php');
//include the classes needed to create and write .xlsx file
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class uploadController extends Controller{

	private $model;
	private $homeController;
	
	function __construct(){	
		parent::__construct();
		$this->model = $this->load->model('homeModel');
		
		$getSess = $this->isLoggedIn('userdata');
		if(empty($getSess)){
			header('Location: '.APPS_URL.'login'); exit;
		}else if(!empty($getSess) && $getSess['role']!=1){
			header('Location: '.APPS_URL.'login'); exit;
		}
	}

	function index(){

		$data = [];
		//$data['quarterlist'] = $this->getQuarterList();
		$this->view('upload/index', $data);
	}

	function upload_files(){
		$path = 'assets/documents/rft/';
		if(isset($_POST['submit_upload'])){
			$getDate = '01-'.$_POST['uploadedmonth'];
			$getMonthYear = !empty($_POST['uploadedmonth']) ? date('m_Y', strtotime($getDate)) : date('m_Y');
			if(!file_exists($path.$getMonthYear)){
				mkdir($path.$getMonthYear);
			}

			if(isset($_FILES['rft_data'])){
				$filename = $_FILES['rft_data']['name'];
				$source = $_FILES['rft_data']['tmp_name'];
				$type = $_FILES['rft_data']['type'];

				$name = explode('.', $filename); 
				$ext = end($name);
				$newfilename = 'rft_for_the_month_of_'.$getMonthYear.'.'.$ext;
				$textFilename = $path.$getMonthYear.'/rft_for_the_month_of_'.$getMonthYear.'.txt';
				if(!empty($_POST['override']) || !file_exists($textFilename)){
					if($this->getNumberOfPages($source)==1){
						if($ext=="xls" || $ext=="xlsx"){
							$saved_file_location = $path.$getMonthYear.'/'.$newfilename;  
							if(move_uploaded_file($source, $saved_file_location)) {					
								if($this->writeIntoDatabase($saved_file_location, $path, $getMonthYear)){
									$data['succ'] = 'Successfully uploaded';	
								}else{
									$data['error'] = 'Something is wrong. Please try again';
								}				
							}else{
								$data['error'] = 'Something is wrong. Please try again.';
							}
						}else{
							$data['error'] = 'Please check the file format. Only xls or xlsx is allowed.';
						}
					}else{
						$data['error'] = 'Please create a separate RFT data list excel file and uplaod again.';
					}
				}else{
					$data['error'] = 'File already exists. If you need to override, please contact of development team.';
				}
			}else{
				$data['error'] = 'Please upload the RFT file';
			}
		}else{
			$data['error'] = 'Please choose the file';
		}

		$this->view('upload/index', $data);
	}	

	function writeIntoDatabase($inputFileName, $absolute_path, $monthYear){
		$inputFileType = 'Xlsx';
		/**  Create a new Reader of the type defined in $inputFileType  **/
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
		/**  Load $inputFileName to a Spreadsheet Object  **/
		$spreadsheet = $reader->load($inputFileName);

		$j=0;
		$selectedColumnOnly = 0;
		$arr = array();
		foreach ($spreadsheet->setActiveSheetIndex(0)->getRowIterator() as $row) {
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);
			foreach ($cellIterator as $cell) {
				$value = $cell->getCalculatedValue();
				
				$pliSql = "SELECT `employee_id` FROM `users` WHERE LOWER(name)='".strtolower($value)."'";
				$res = $this->model->selectSingleRecordFromPLI($pliSql);
				
				if(count($res)>0){
					/*$sql = "INSERT INTO rft_data (`emp_id`, `emp_name`, `approved_jobs`, `non_approved_jobs`, `rft_month`) VALUES ('John', 'Doe', 'john@example.com')";
					$this->model->insertIntoCalculator($sql);*/
					$arr[$value] = $res['employee_id'];
				}/*else{
					$arr[$j][] = $value;
				}*/
			}
			$j++;			
		}
		$this->pr($arr);
		exit;
		
		/*$serialize = serialize($arr);		
		$nfile = $absolute_path.$monthYear.'/rft_for_the_month_of_'.$monthYear.'.txt';
		if(!is_file($nfile)){			
			file_put_contents($nfile, $serialize);
		}else{
			unlink($nfile);
			file_put_contents($nfile, $serialize);
		}

		if(file_exists($nfile)){
			unlink($inputFileName);
			return true;
		}else{
			return false;
		}*/

	}

	function writeIntoTxt($inputFileName, $absolute_path, $monthYear){
		$inputFileType = 'Xlsx';
		/**  Create a new Reader of the type defined in $inputFileType  **/
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
		/**  Load $inputFileName to a Spreadsheet Object  **/
		$spreadsheet = $reader->load($inputFileName);

		$j=0;
		$selectedColumnOnly = 0;
		$arr = array();
		foreach ($spreadsheet->setActiveSheetIndex(0)->getRowIterator() as $row) {
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);
			foreach ($cellIterator as $cell) {
				$value = $cell->getCalculatedValue();
				$arr[$j][] = $value;
			}
			$j++;			
		}
		
		$serialize = serialize($arr);		
		$nfile = $absolute_path.$monthYear.'/rft_for_the_month_of_'.$monthYear.'.txt';
		if(!is_file($nfile)){			
			file_put_contents($nfile, $serialize);
		}else{
			unlink($nfile);
			file_put_contents($nfile, $serialize);
		}

		if(file_exists($nfile)){
			unlink($inputFileName);
			return true;
		}else{
			return false;
		}

	}

	function getNumberOfPages($inputFileName){
		$inputFileType = 'Xlsx';
		/**  Create a new Reader of the type defined in $inputFileType  **/
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
		/**  Load $inputFileName to a Spreadsheet Object  **/
		$spreadsheet = $reader->load($inputFileName);

		return $spreadsheet->getSheetCount();
	}

	function getQuarterList(){
		require_once(APPS_PATH.'/application/controller/homeController.class.php');
		$this->homeController = new homeController();

		return $this->homeController->getSelectedQuarterDate(date('m'), date('Y'), date('Y')-1);
	}
}


?>
<?php

class homeController extends Controller{

	private $model;
	
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
		$res = $this->getSelectedQuarterDate(date('m'), date('Y'), date('Y')-1);
		$data['quarterlyDate'] = $res;
		$data['regions'] = $this->getRegion();
		$data['channels'] = $this->getChannel();
		
		$this->view('home/index', $data);
	}

	function getplidata(){
		$isPage = !empty($this->url_segments(3)) && $this->url_segments(3)=='page' ? 'pagination' : '';
		$page = !empty($this->url_segments(4)) ? $this->url_segments(4) : 1;

		$data['regions'] = $this->getRegion();
		$data['channels'] = $this->getChannel();

		if(!empty($_POST['fromdate'])){
			$startDate = date('Y-m-d 00:00:00', strtotime('01-'.$_POST['fromdate']));
			$_SESSION['fromdate'] = '';
		}else if(!empty($_SESSION['fromdate'])){
			$startDate = $_SESSION['fromdate'];
		}else{ 
			$startDate = '';
		}

		if(!empty($_POST['todate'])){
			$endDate = date('Y-m-d 00:00:00', strtotime('30-'.$_POST['todate']));
			$_SESSION['todate'] = '';
		}else if(!empty($_SESSION['todate'])){
			$endDate = $_SESSION['todate'];
		}else{ 
			$endDate = '';
		}

		$region = '';
		$channel = '';
		if(!empty($_POST['region'])){
			$region = $_POST['region'];
			$_SESSION['region'] = $_POST['region'];
		}else if(!empty($_SESSION['region']) && $isPage=='pagination'){
			$region = $_SESSION['region'];
		}else if(!empty($_SESSION['region']) && $isPage==''){
			unset($_SESSION['region']);
		}

		if(!empty($_POST['channel'])){
			$channel = $_POST['channel'];
			$_SESSION['channel'] = $_POST['channel'];
		}else if(!empty($_SESSION['channel']) && $isPage=='pagination'){
			$channel = $_SESSION['channel'];
		}else if(!empty($_SESSION['channel']) && $isPage==''){
			unset($_SESSION['channel']);
		}

		if(!empty($_POST['quarter'])){
			$startDate = date('Y-m-d 00:00:00', strtotime(substr($_POST['quarter'], 4, 10)));
			$endDate = date('Y-m-d 23:59:59', strtotime(substr($_POST['quarter'], -11, 10)));
		}

		if(!empty($startDate) && !empty($endDate)){
			$_SESSION['fromdate'] = $startDate;
			$_SESSION['todate'] = $endDate;
		}

		$data['quarterlyDate'] = $this->getSelectedQuarterDate(date('m'), date('Y'), date('Y')-1);

		$paginationUrl = 'home/getplidata/page';
		$setLimit = 10;
		$options = [];
		$options['page'] = $page;
		$options['limit'] = $setLimit;
		$options['region'] = $region;		
		$options['channel'] = $channel;
		$count = $this->getPKTCount($startDate, $endDate, $options);

		$data['pktdata'] = $this->getDesignersQuaterlyPKTScore($startDate, $endDate, $options); //$page, $setLimit

		$empIds= array();
		if(!empty($data['pktdata'])){
			foreach ($data['pktdata'] as $key => $value) {
				$empIds[] = $value['empid'];
			}
		}

		$data['plidata'] = count($empIds) > 0 ? $this->getDesignersQuaterlyAttendance($startDate, $endDate, implode(',', $empIds), $options) : [];
		$data['num_of_days'] = ceil(abs(strtotime($endDate) - strtotime($startDate)) / 86400);
		$data['num_sat_and_sun'] = $this->getSatNSundayInAQuarter($startDate, $endDate);
		$data['num_of_holiday'] = !empty($_POST['num_of_holiday']) ? $_POST['num_of_holiday'] : 0;

		//$data['rft'] = $this->getDataFromTxt($startDate, $endDate, $data['num_of_days']);

		//$this->pr($data['rft']);exit;

		$data['pagi'] = $this->pagination($paginationUrl, $count, $setLimit, $page);
		$data['setPagi'] = ($count>$setLimit) ? true : false;

		$this->view('home/index', $data);
	}	
	
	function getDesignersQuaterlyPKTScore($startDate, $endDate, $options){ //$page='', $limit=''
		$page = !empty($options['page']) ? $options['page'] : ''; 
		$limit = !empty($options['limit']) ? $options['limit'] : '';
		$region = !empty($options['region']) ? $options['region'] : '';
		$channel = !empty($options['channel']) ? $options['channel'] : '';

		$offset = ($page-1)*$limit;
		$setLimit = '';
		if($page!='' && $limit!=''){
			$setLimit = "LIMIT $offset, $limit";
		}

		$where = "WHERE `ur`.`date`>='$startDate' AND `ur`.`date`<='$endDate'";
		if($region!=''){
			$where .= " AND `u`.`region`='$region'";
		}
		if($channel!=''){
			$where .= " AND `u`.`channel`='$channel'";
		}

		$sql = "SELECT COUNT(`ur`.`id`) as pktcnt, SUM(CAST(`ur`.`percentage` AS Integer)) as `total_percentage`, group_concat(`ur`.`percentage` separator '|') as `percentages`, group_concat(`ur`.`date` separator '|') as `completed_dates`, `u`.`emp_name`, `u`.`empid`, `u`.`region`, `u`.`channel` FROM `user_result` as ur JOIN `users` as u ON `ur`.`user_id`=`u`.`id` $where GROUP BY `ur`.`user_id` ORDER BY `ur`.`date` desc $setLimit"; //HAVING COUNT(id)>2
		$getRows = $this->model->selectAllRecordsFromPKT($sql);
		return $getRows;
	}
	
	function getDesignersQuaterlyAttendance($startDate, $endDate, $empIds){
		$sql = "SELECT count(`employee_id`) as cnt, `employee_id`, `leave_type`, from_unixtime(`start_date`), from_unixtime(`end_date`) FROM `resource_leave_allocation` WHERE (`start_date`>='".strtotime($startDate)."' AND `start_date`<='".strtotime($endDate)."') AND (`leave_type`='Unapproved Leave' OR `leave_type`='Unapporve Unschedule Leave') AND `employee_id` IN (".$empIds.") GROUP BY `employee_id`, `leave_type` ORDER BY `employee_id` desc"; //AND `employee_id` IN (".$empIds.") 
		$getRows = $this->model->selectAllRecordsFromPLI($sql);

		$result = [];
		if(!empty($getRows)){
			foreach ($getRows as $key => $value) {
				if($value['leave_type'] == 'Unapporve Unschedule Leave'){
					$result[$value['employee_id']]['uul'] = $value['cnt']; // uul means "Unapporve Unschedule Leave"
				}
				if($value['leave_type'] == 'Unapproved Leave'){
					$result[$value['employee_id']]['ul'] = $value['cnt']; // ul means "Unapproved Leave"
				}
			}
		}

		return $result;
	}

	function getPKTCount($startDate, $endDate, $options){
		$region = !empty($options['region']) ? $options['region'] : '';
		$channel = !empty($options['channel']) ? $options['channel'] : '';

		$where = "WHERE `ur`.`date`>='$startDate' AND `ur`.`date`<='$endDate'";
		if($region!=''){
			$where .= " AND `u`.`region`='$region'";
		}
		if($channel!=''){
			$where .= " AND `u`.`channel`='$channel'";
		}

		$sql = "SELECT `ur`.`id` FROM `user_result` as ur LEFT JOIN `users` as u on `ur`.`user_id`=`u`.`id` $where GROUP BY `ur`.`user_id`";
		$getRows = $this->model->getNumRowsFromPKT($sql);

		return $getRows;		
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

	function getDataFromTxt($startDate, $endDate, $days){
		$getStartMonth = date('m', strtotime($startDate));
		$getStartYear = date('Y', strtotime($startDate));		
		echo $noOfMonth = round($days/30);
		$finalArr = [];
		$month = $getStartMonth;
		$year = $getStartYear;
		for($i=1; $i<=$noOfMonth; $i++){
			$finalArr[] = $this->getFileData($month, $year);		

			if($month==12){
				$month = 1;
				$year = $getStartYear + 1;
			}else{
				$month++;
			}
		}
		
		return $finalArr;
	}

	function getFileData($month, $year){		
		if(strlen($month)==1){
			$month = '0'.$month;
		}
		$folderName = $month.'_'.$year;
		$nfile = APPS_PATH.'/assets/documents/rft/'.$folderName.'/rft_for_the_month_of_'.$folderName.'.txt';
		$myfile = fopen($nfile, "r");
		$txtData = fread($myfile,filesize($nfile));	
		$unserializeData = unserialize($txtData);
		$finalArr = [];
		if(!empty($unserializeData)){
			foreach ($unserializeData as $key => $value) {
				$finalArr[$key] = $value;
			}
		}
		return $finalArr;
	}

	function getQuarterByMonth($monthNumber) {
	  return floor(($monthNumber - 1) / 3) + 1;
	}

	function getSelectedQuarterDate($curMonth, $curYrs, $prevYrs) {
		$quarter = '';
		if($curMonth>=1 && $curMonth<=3){
			$quarter = '[{"date": "Q1 (01-01-'.$curYrs.' To 31-03-'.$curYrs.')", "year": "Current Year"}, {"date": "Q2 (01-04-'.$prevYrs.' To 30-06-'.$prevYrs.')", "year": "Previous Year"}, {"date": "Q3 (01-07-'.$prevYrs.' To 30-09-'.$prevYrs.')", "year": "Previous Year"}, {"date": "Q4 (01-10-'.$prevYrs.' To 31-12-'.$prevYrs.')", "year": "Previous Year"}]';
		}else if($curMonth>=4 && $curMonth<=6){
			$quarter = '[{"date": "Q1 (01-01-'.$curYrs.' To 31-03-'.$curYrs.')", "year": "Current Year"}, {"date": "Q2 (01-04-'.$curYrs.' To 30-06-'.$curYrs.')", "year": "Current Year"}, {"date": "Q3 (01-07-'.$prevYrs.' To 30-09-'.$prevYrs.')", "year": "Previous Year"}, {"date": "Q4 (01-10-'.$prevYrs.' To 31-12-'.$prevYrs.')", "year": "Previous Year"}]';
		}else if($curMonth>=7 && $curMonth<=9){
			$quarter = '[{"date": "Q1 (01-01-'.$curYrs.' To 31-03-'.$curYrs.')", "year": "Current Year"}, {"date": "Q2 (01-04-'.$curYrs.' To 30-06-'.$curYrs.')", "year": "Current Year"}, {"date": "Q3 (01-07-'.$curYrs.' To 30-09-'.$curYrs.')", "year": "Current Year"}, {"date": "Q4 (01-10-'.$prevYrs.' To 31-12-'.$prevYrs.')", "year": "Previous Year"}]';
		}else if($curMonth>=9 && $curMonth<=12){
			$quarter = '[{"date": "Q1 (01-01-'.$curYrs.' To 31-03-'.$curYrs.')", "year": "Current Year"}, {"date": "Q2 (01-04-'.$curYrs.' To 30-06-'.$curYrs.')", "year": "Current Year"}, {"date": "Q3 (01-07-'.$curYrs.' To 30-09-'.$curYrs.')", "year": "Current Year"}, {"date": "Q4 (01-10-'.$curYrs.' To 31-12-'.$curYrs.')", "year": "Current Year"}]';
		}
		return $quarter;
	}

	function getSatNSundayInAQuarter($startDate, $endDate){
		$num_sat_and_sundays = 0;             
		for ($i = 0; $i < ((strtotime($endDate) - strtotime($startDate)) / 86400); $i++){
		    if(date('l',strtotime($startDate) + ($i * 86400)) == 'Sunday'){
		        $num_sat_and_sundays++;
		    }
		    if(date('l',strtotime($startDate) + ($i * 86400)) == 'Saturday'){
		    	$num_sat_and_sundays++;
		    }    
		}
		return $num_sat_and_sundays;
	}

	function mysort($arr){
		$fin = $arr;
		$len = count($fin);
		for($i=0; $i<$len; $i++) {
			for($j=0; $j<$len; $j++){
				if(array_key_exists($j + 1, $fin) && $fin[$j] > $fin[$j + 1]){
					$tem = $fin[$j];
					$fin[$j] = $fin[$j + 1];
					$fin[$j + 1] = $tem;
				}
			}
		}
		return $fin;
	}
}


?>
<?php

class homeModel extends Model{
	private $db_sat; 
	private $db_lams;
	private $db_calc;
	
	function __construct(){	
		//parent::__construct();
		$this->db_sat = $this->diff_conn(DB_HOST, DB_USER, DB_PASS, DB_NAME_SAT);
		$this->db_lams = $this->diff_conn(DB_HOST, DB_USER, DB_PASS, DB_NAME_LAMS);
		$this->db_calc = $this->diff_conn(DB_HOST, DB_USER, DB_PASS, DB_NAME_CALC);	
	}	
	
	function selectSingleRecordFromPKT($sql){		
		$result = mysqli_query($this->db_sat, $sql);
		if (mysqli_num_rows($result) > 0) {
			$rows = mysqli_fetch_assoc($result);
			return $rows;
		}else{
			return false;
		}			
	}
	
	function selectAllRecordsFromPKT($sql){	
		$result = mysqli_query($this->db_sat, $sql);
		if (mysqli_num_rows($result) > 0) {
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			return $rows;
		}else{
			return 0;
		}			
	}
	
	function getNumRowsFromPKT($sql){	
		$result = mysqli_query($this->db_sat, $sql);
		if (mysqli_num_rows($result) > 0) {
			$rowcount=mysqli_num_rows($result);;
			return $rowcount;
		}else{
			return false;
		}			
	}	
	
	function selectAllRecordsFromPLI($sql){		
		$result = mysqli_query($this->db_lams, $sql);
		if (mysqli_num_rows($result) > 0) {
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			return $rows;
		}else{
			return false;
		}			
	}
	
	function selectSingleRecordFromPLI($sql){		
		$result = mysqli_query($this->db_lams, $sql);
		if (mysqli_num_rows($result) > 0) {
			$rows = mysqli_fetch_assoc($result);
			return $rows;
		}else{
			return false;
		}			
	}
	
	function updateIntoPLI($sql){		
		$result = mysqli_query($this->db_lams, $sql);
		if (mysqli_affected_rows($this->db_lams)) {
			return true;
		}else{
			return false;
		}			
	}	
	
	function insertIntoCalculator($sql){		
		$result = mysqli_query($this->db_calc, $sql);
		if (mysqli_affected_rows($this->db_calc)) {
			return mysqli_insert_id($this->db_calc);
		}else{
			return false;
		}			
	}
	
	function updateIntoCalculator($sql){		
		$result = mysqli_query($this->db_calc, $sql);
		if (mysqli_affected_rows($this->db_calc)) {
			return true;
		}else{
			return false;
		}			
	}	
	
	function selectAllRecordsFromCalculator($sql){		
		$result = mysqli_query($this->db_calc, $sql);
		if (mysqli_num_rows($result) > 0) {
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			return $rows;
		}else{
			return false;
		}			
	}
	
	function selectSingleRecordFromCalculator($sql){		
		$result = mysqli_query($this->db_calc, $sql);
		if (mysqli_num_rows($result) > 0) {
			$rows = mysqli_fetch_assoc($result);
			return $rows;
		}else{
			return false;
		}			
	}	
}


?>

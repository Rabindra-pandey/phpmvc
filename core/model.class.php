<?php

class Model{

	private $conn;
	private $diffconn;
	private $db;

	function __construct(){	
		if(!empty(DB_NAME)){
			$this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);		
			if ($this->db->connect_error) {
				die("Connection failed: " . $this->conn->connect_error);
			}
		}
	}

	function diff_conn($dbhost, $dbuser, $dbpass, $dbName){
		$this->diffconn = new mysqli($dbhost, $dbuser, $dbpass, $dbName);
		if ($this->diffconn->connect_error) {
			die("Connection failed: " . $this->diffconn->connect_error);
		}
		return $this->diffconn;
	}
}


?>

<?php
Class dbObj{
	var $servername = DB_HOST;
	var $username = DB_USER;
	var $password = DB_PASSWORD;
	var $dbname = DB_NAME;
	var $conn;
	
	function __construct(){
		$this->getConnstring();
	}

	function getConnstring() {
		$con = mysqli_connect($this->servername, $this->username, $this->password, $this->dbname) or die("Connection failed: " . mysqli_connect_error());
 
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		} else {
			$this->conn = $con;
		}
		return $this->conn;
	}

	public function run_query($query ){
		$this->conn->query($query );
	}

	public function load_data($table ){
		$query = "select * from {$table }";
		$result = $this->conn->query($query );
		return $result;
	}
	
	public function update_query($query ){
		$this->conn->query($query );
		return mysqli_insert_id($this->conn );
	}
}
?>
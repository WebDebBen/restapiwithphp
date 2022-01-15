<?php
Class dbObj{
	var $servername = DB_HOST;
	var $username = DB_USER;
	var $password = DB_PASSWORD;
	var $dbname = DB_NAME;
	var $conn;
	
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

	function show_tables(){
		$query = "show tables";
		$result = $this->conn->query($query );
		$tables = [];
		if($result){
			while ($row = $result->fetch_array()){
				array_push($tables, $row[0] );
			}
		}
		return $tables;
	}
}

?>
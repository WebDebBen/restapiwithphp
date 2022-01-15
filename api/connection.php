<?php
Class dbObj{
	var $servername = "localhost";
	var $username = "root";
	var $password = "";
	var $dbname = "crypto";
	var $conn;
	
	function getConnstring() {
		$con = mysqli_connect($this-&gt;servername, $this-&gt;username, $this-&gt;password, $this-&gt;dbname) or die("Connection failed: " . mysqli_connect_error());
 
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		} else {
			$this->conn = $con;
		}
		return $this->conn;
	}
}

?>
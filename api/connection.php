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

	function table_infos($tables ){
		$infos = [];
		foreach($tables as $item ){
			$query = "SELECT
						ic.COLUMN_NAME, ic.COLUMN_DEFAULT, ic.IS_NULLABLE, ic.DATA_TYPE, ic.CHARACTER_MAXIMUM_LENGTH,
						ic.COLUMN_TYPE, ic.COLUMN_KEY, ik.REFERENCED_TABLE_NAME, ik.REFERENCED_COLUMN_NAME
					FROM INFORMATION_SCHEMA.COLUMNS ic
					LEFT JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE ik on ik.REFERENCED_TABLE_SCHEMA = '" . DB_NAME . 
					"' and ik.TABLE_NAME = ic.TABLE_NAME and ik.COLUMN_NAME=ic.COLUMN_NAME
					WHERE ic.TABLE_NAME = '" . $item . "' ORDER BY ic.ORDINAL_POSITION";

			$result = $this->conn->query($query );
			if ($result ){
				$info = [];
				$info["table_name"] = $item;
				$info["columns"] = [];
				while($row = $result->fetch_array()){
					$item = [];
					$item["column_name"] = $row["COLUMN_NAME"];
					$item["column_default"] = $row["COLUMN_DEFAULT"];
					$item["is_nullable"] = $row["IS_NULLABLE"];
					$item["data_type"] = $row["DATA_TYPE"];
					$item["character_maximum_length"] = $row["CHARACTER_MAXIMUM_LENGTH"];
					$item["column_type"] = $row["COLUMN_TYPE"];
					$item["column_key"] = $row["COLUMN_KEY"];
					$item["referenced_table_name"] = $row["REFERENCED_TABLE_NAME"];
					$item["referenced_column_name"] = $row["REFERENCED_COLUMN_NAME"];
					$info["columns"][] = $item;
				}
				array_push($infos, $info );
			}
		}
		return $infos;
	}
}

?>
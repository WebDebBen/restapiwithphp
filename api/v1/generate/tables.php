<?php
	$request_method=$_SERVER["REQUEST_METHOD"];
	
    switch($request_method)
	{
		case 'GET':
			handle_get_method();
			break;
		default:
			handle_post_method();
			break;
	}

	function handle_get_method(){

	}

	function handle_table_infos(){
		$db = $GLOBALS["db"];
        $tables = $_POST["tables"];
        $tables = json_decode($tables );
		$result = ["status"=> "success"];
		$data = $db->table_infos($tables );
		$result["data"] = $data;
		response_data(200, $result );
	}

	function handle_sub_method($seg ){
		$result = ["status"=> "success"];
		response_data(200, $result );
	}

	function handle_post_method(){
		$sub_root_ind = 4;
		$sub_folder_ind = 5;

		$uri = $_SERVER["REQUEST_URI"];
		$segments = explode('/', $uri );
		
		if (count($segments ) == $sub_folder_ind ){ 			// api/v1/tables
			handle_table_infos();
		}else{
			handle_sub_method($segments[$sub_folder_ind]);
		}
	}

	function response_data($code, $data ){
        header('X-PHP-Response-Code: '.$code, true, $code);
		header('Content-Type: application/json');
		echo json_encode($data );
	}

?>
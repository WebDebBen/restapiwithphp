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

	function handle_get_method(){}

    function handle_post_method(){
        $data = $_POST["table_info"];
        $data = json_decode($data );
        
        foreach($data as $item ){
            generate_api($item );
        }
    }

    function generate_api($item ){
        
    }
?>
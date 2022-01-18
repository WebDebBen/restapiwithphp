<?php

    include_once("./config.php");
    include_once("./connection.php");
    
    $db = new dbObj();
	$connection =  $db->getConnstring();
    
	$uri = preg_replace("/\?.*$/","",$_SERVER["REQUEST_URI"]);
    //$uri = $_SERVER["REQUEST_URI"];
    $uri_arr = explode("/", $uri ); 
    $call = $uri_arr[count($uri_arr ) - 2 ];
    $action = $uri_arr[count($uri_arr ) - 1 ];

    $model_path = "./" . $call . "/" . $call . ".php";
    $action_path = "./" . $call . "/" . $action . ".php";

    if ($call == "generate"){
        if (!file_exists($action_path )){
            http_response_code(404);
            echo "Page Not Found";
        }else{
            include_once($action_path );
        }
    }else{
        if (!file_exists($model_path ) || !file_exists($action_path )){
            http_response_code(404);
            echo "Page Not Found";
        }else{
            include_once($model_path );
            include_once($action_path );
        }
    }    

?>
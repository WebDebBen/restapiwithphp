<?php
include_once("../config.php");
include_once("../connection.php");
include_once("./gen.php");

extract($_POST );
switch($type ){
    case "sql":
        generate_sql($json_data );
        break;
    case "html":
        generate_html($json_data );
        break;
    case "javascript":
        generate_javascript($json_data );
        break;
    case "php":
        generate_php($json_data );
        break;
    case "run":
        generate_run($json_data );
        break;
}

function generate_sql($data ){
    $sql = generate_content($data, "sql");
    echo json_encode($sql );
}

function generate_html($data ){
    $sql = generate_content($data, "html");
    echo json_encode($sql );
}

function generate_javascript($data ){
    $sql = generate_content($data, "javascript");
    echo json_encode($sql );
}

function generate_php($data ){
    $sql = generate_content($data, "php");
    echo json_encode($sql );
}

function generate_run($data ){
    $run = generate_content($data, "run");
    echo json_encode($run );
}
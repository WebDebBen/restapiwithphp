<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    $get_params = isset($_GET) ? $_GET : [];

    $model = new TbaccountsGroupsMembers($connection );
    $data = $model->read($get_params );

    http_response_code(200);
    echo json_encode($data);
?>
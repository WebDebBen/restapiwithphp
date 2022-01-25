<?php
    include_once("../../config.php");
    include_once("./test_sql.php");
    include_once("../db.php");
    $db = new dbObj();

    extract($_POST);
    switch($type ){
        case "init_table":
            init_table();
            break;
        case "delete":
            delete_tr($id);
            break;
        case "save":
            save_tr($id, $a, $b, $c);
            break;
    }

    function init_table(){
        $query = $GLOBALS["query"];
        $db = $GLOBALS["db"];
        $db->run_query($query );
        $result = $db->load_data("test");

        $data = [];
		if($result){
			while ($row = $result->fetch_array()){
				$item = [];
                array_push($item, $row["id"]);
				array_push($item, $row["a"]);
				array_push($item, $row["b"]);
				array_push($item, $row["c"]);
				array_push($data, $item );
			}
		}

        echo json_encode(["status"=>"success", "data"=> $data ]);
    }

    function delete_tr($id ){
        $query = "delete from test where id={$id}";
        $db = $GLOBALS["db"];
        $db->run_query($query );
        echo json_encode(["status"=> "success"]);
    }

    function save_tr($id, $a, $b, $c){
        $db = $GLOBALS["db"];
        if ($id == "-1"){
            $query = "insert into test set a='{$a}', b='{$b}', c='{$c}'";
        }else{
            $query = "update test set a='{$a}', b='{$b}', c='{$c}' where id={$id}";
        }
        $id = $db->update_query($query );
        echo json_encode(["status"=> "success", "id"=> $id ]);
    }
?>
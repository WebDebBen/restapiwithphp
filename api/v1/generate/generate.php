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
        $table_infos = $_POST["table_infos"];

        $table_infos = json_decode($table_infos );

        foreach($table_infos as $item ){
            generate_api($item );
        }
    }

    
    function generate_api($item ){
        $table_name = $item->table_name;
        $columns = $item->columns; 
        if (file_exists($table_name )){
            rrmdir($table_name);
        }
        mkdir($table_name, 0700);
    
        
        // create create.php 
        $content = generate_create_php($table_name );
        $file_name = $table_name . "/create.php";
        $myfile = fopen($file_name, "w") or die("Unable to open file!");
        fwrite($myfile, $content);
        fclose($myfile);

        $content = generate_update_php($table_name );
        $file_name = $table_name . "/update.php";
        $myfile = fopen($file_name, "w") or die("Unable to open file!");
        fwrite($myfile, $content);
        fclose($myfile);

        $content = generate_delete_php($table_name );
        $file_name = $table_name . "/delete.php";
        $myfile = fopen($file_name, "w") or die("Unable to open file!");
        fwrite($myfile, $content);
        fclose($myfile);

        $content = generate_addupdate_php($table_name );
        $file_name = $table_name . "/addupdate.php";
        $myfile = fopen($file_name, "w") or die("Unable to open file!");
        fwrite($myfile, $content);
        fclose($myfile);

        
        $content = generate_read_php($table_name );
        $file_name = $table_name . "/read.php";
        $myfile = fopen($file_name, "w") or die("Unable to open file!");
        fwrite($myfile, $content);
        fclose($myfile);

        $content = generate_model_php($table_name, $columns );
        $file_name = $table_name . "/$table_name.php";
        $myfile = fopen($file_name, "w") or die("Unable to open file!");
        fwrite($myfile, $content);
        fclose($myfile);
    }

    function generate_create_php($table_name ){
        $table_class = dashesToCamelCase($table_name); 
        $php = "<?php\n";
        $php .= "\t" . 'header("Access-Control-Allow-Origin: *");' . "\n";
        $php .= "\t" . 'header("Content-Type: application/json; charset=UTF-8");' . "\n";
        $php .= "\t" . 'header("Access-Control-Allow-Methods: POST");' . "\n";
        $php .= "\t" . 'header("Access-Control-Max-Age: 3600");' . "\n";
        $php .= "\t" . 'header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");' . "\n";
        $php .= "\t" . '$get_params = isset($_POST) ? $_POST : [];' . "\n";
        $php .= "\t" . '$model = new ' . $table_class . '($connection );' . "\n";
        $php .= "\t" . '$data = $model->create($get_params );' . "\n";
        $php .= "\t" .  'if ($data["result"] == true ){' . "\n";
        $php .= "\t" . "\t" . 'http_response_code(200);' . "\n";
        $php .= "\t" . '}else{' . "\n";
        $php .= "\t" . 'http_response_code(500); }' . "\n";
        $php .= "\t" . 'echo json_encode($data);' . "\n ?>";
        return $php;
    }

    function generate_update_php($table_name ){
        $table_class = dashesToCamelCase($table_name );
        $php = '<?php ' . "\n";
        $php .= "\t" . 'header("Access-Control-Allow-Origin: *"); ' . "\n";
        $php .= "\t" . 'header("Content-Type: application/json; charset=UTF-8"); ' . "\n";
        $php .= "\t" . 'header("Access-Control-Allow-Methods: POST"); ' . "\n";
        $php .= "\t" . 'header("Access-Control-Max-Age: 3600"); ' . "\n";
        $php .= "\t" . 'header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); ' . "\n";

        $php .= "\t" . '$get_params = isset($_POST) ? $_POST : []; ' . "\n";

        $php .= "\t" . '$model = new ' . $table_class . '($connection );' . "\n";
        $php .= "\t" . '$data = $model->update($get_params ); ' . "\n";

        $php .= "\t" . 'if ($data["result"] == true ){ ' . "\n";
            $php .= "\t" . 'http_response_code(200); ' . "\n";
        $php .= "\t" . '}else{ ' . "\n";
            $php .= "\t" . 'http_response_code(500); ' . "\n";
        $php .= "\t" . '} ' . "\n";
        $php .= "\t" . 'echo json_encode($data); ' . "\n";
        $php .= '?> ' . "\n";
        return $php;
    }

    function  generate_delete_php($table_name ){
        $table_class = dashesToCamelCase($table_name );
        $php = '<?php' ."\n";
        $php .= "\t" . 'header("Access-Control-Allow-Origin: *");' ."\n";
        $php .= "\t" . 'header("Content-Type: application/json; charset=UTF-8");' ."\n";
        $php .= "\t" . 'header("Access-Control-Allow-Methods: POST");' ."\n";
        $php .= "\t" . 'header("Access-Control-Max-Age: 3600");' ."\n";
        $php .= "\t" . 'header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");' ."\n";
        $php .= "\t" . '$get_params = isset($_POST) ? $_POST : [];' ."\n";
        $php .= "\t" . '$model = new ' . $table_class . '($connection );' . "\n";
        $php .= "\t" . '$data = $model->delete($get_params );' ."\n";
        $php .= "\t" . 'if ($data["result"] == true ){' ."\n";
            $php .= "\t" . "\t" . 'http_response_code(200);' ."\n";
        $php .= "\t" . '}else{' ."\n";
            $php .= "\t" . "\t" . 'http_response_code(500);' ."\n";
        $php .= "\t" . '}' ."\n";
        $php .= "\t" . 'echo json_encode($data);' ."\n";
        $php .= "\t" . '?>' ."\n";
        return $php;
    }

    function generate_addupdate_php($table_name ){
        $table_class = dashesToCamelCase($table_name );
        $php = "<?php \n";
        $php .= "\t" . 'header("Access-Control-Allow-Origin: *");' .  "\n";
        $php .= "\t" . 'header("Content-Type: application/json; charset=UTF-8");' . "\n";
        $php .= "\t" . 'header("Access-Control-Allow-Methods: POST");' . "\n";
        $php .= "\t" . 'header("Access-Control-Max-Age: 3600");' . "\n";
        $php .= "\t" . 'header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");' . "\n";
        $php .= "\t" . '$get_params = isset($_POST) ? $_POST : [];' . "\n";
        $php .= "\t" . '$model = new ' . $table_class . '($connection );' . "\n";
        $php .= "\t" . '$data = $model->add_or_update($get_params );' . "\n";
        $php .= "\t" . 'if ($data["result"] == true ){' . "\n";
            $php .= "\t" . "\t" . 'http_response_code(200);' . "\n";
        $php .= "\t" . '}else{' . "\n";
            $php .= "\t" . "\t" . 'http_response_code(500);' . "\n";
        $php .= "\t" . '}' . "\n";
        $php .= "\t" . 'echo json_encode($data);' . "\n";
        $php .= '?>' . "\n";
        return $php;
    }

    function generate_read_php($table_name ){
        $table_class = dashesToCamelCase($table_name );
        $php = "<?php \n";
        $php .= "\t" . 'header("Access-Control-Allow-Origin: *");' . "\n";
        $php .= "\t" . 'header("Content-Type: application/json; charset=UTF-8");' . "\n";
        $php .= "\t" . '$get_params = isset($_GET) ? $_GET : [];' . "\n";
        $php .= "\t" . '$model = new ' . $table_class . '($connection );' . "\n";
        $php .= "\t" . '$data = $model->read($get_params );' . "\n";
        $php .= "\t" . 'http_response_code(200);' . "\n";
        $php .= "\t" . 'echo json_encode($data);' . "\n";
        $php .= '?>' . "\n";
        return $php;
    }

    function generate_model_php($table_name, $columns ){
        $table_class = dashesToCamelCase($table_name );

        $php = "<?php \n";
        $php .= add_tab_endline("class $table_class{");
        $php .= add_tab_endline('private $conn;');
        $php .= add_tab_endline('private $table_name = "' . $table_name . '";');
        $php .= add_tab_endline('private $page_len = 10;', 1);
        $php .= add_tab_endline('public function __construct($db ){');
        $php .= add_tab_endline('$this->conn = $db;',2);
        $php .= add_tab_endline("}");
        
        $php .= _model_read_php($columns );
        $php .= _model_create_php($columns );
        $php .= _model_update_php($columns );
        $php .= _model_delete_php($columns );
        $php .= _model_add_or_update($columns );
        $php .= _model_get_record($columns );
        $php .= _model_table_join_data();
        $php .= _model_check_date();
        $php .= _model_join_set_data();

        $php .= "} \n?>";
        return $php;
    }

    function _model_join_set_data(){
        $php = add_tab_endline('function join_set_data($data ){');
            $php .= add_tab_endline('$comma = "";$str = "";', 2);
            $php .= add_tab_endline('foreach($data as $item ){', 2);
                $php .= add_tab_endline('if(!$item["value"]){ continue;}', 3);
                $php .= add_tab_endline('if (strpos($item["type"], "int" ) > -1 || strpos($item["type"], "float" ) > -1 || strpos($item["type"], "double" ) > -1){', 3);
                    $php .= add_tab_endline('$str .= $comma . $item["field"] . "=' . "'" . '" . $item["value"] . "' . "'" . '";', 4);
                $php .= add_tab_endline('}else{', 3);
					$php .= add_tab_endline('$str .= $comma . $item["field"] . "=' . "'" . '" . $item["value"] . "' . "'" . '";', 4);
				$php .= add_tab_endline('}', 3);
                $php .= add_tab_endline('$comma = ",";', 3 );
            $php .= add_tab_endline('}', 2);
            $php .= add_tab_endline('return $str;', 2);
        $php .= add_tab_endline('}');
        return $php;
    }

    function _model_check_date(){
        $php = add_tab_endline('private function check_date($string) {');
            $php .= add_tab_endline('if (strtotime($string)) {return true;}else {return false;}', 2);
        $php .= add_tab_endline('}');
        return $php;
    }

    function _model_table_join_data(){
        $php = add_tab_endline('private function table_join_data($table, $field, $value ){');
            $php .= add_tab_endline('$query = "select * from {$table } where {$field}=$value";',2);
            $php .= add_tab_endline('$result = $this->conn->query($query );',2);
            $php .= add_tab_endline('$data = [];',2);
            $php .= add_tab_endline('if($result){',2);
                $php .= add_tab_endline('while ($row = $result->fetch_array()){',3);
                    $php .= add_tab_endline('array_push($data, $row );',4);
                $php .= add_tab_endline('}',3);
            $php .= add_tab_endline('}',2);
            $php .= add_tab_endline('return $data;',2);
        $php .= add_tab_endline('}');
        return $php;
    }

    function _model_get_record($columns ){
        $primary_field = get_primary_field($columns );

        $php = add_tab_endline('private function get_record($id ){' );
        $php .= add_tab_endline('$query = "select * from {$this->table_name } where ' . $primary_field . '={$id}";', 2);
        $php .= add_tab_endline('$result = $this->conn->query($query );', 2);
        $php .= add_tab_endline('return $result;', 2);

        $php .= add_tab_endline('}');
        return $php;
    }

    function _model_add_or_update($columns ){
        $primary_field = get_primary_field($columns );

        $php = add_tab_endline('public function add_or_update($params ){');
        $php .= add_tab_endline('extract($params );', 2);
        $php .= add_tab_endline('$' . $primary_field . ' = isset($' . $primary_field . ') ? $' . $primary_field . ' : "";', 2);
        $php .= add_tab_endline('$tmp = [];', 2 );
        $php .= add_tab_endline('if ($' . $primary_field . ' == "" ){', 2 );
            $php .= add_tab_endline('$tmp = $this->create($params );', 3 );
        $php .= add_tab_endline('}else{', 2);
            $php .= add_tab_endline('$query = "select * from {$this->table_name} where ' . $primary_field . '={$' . $primary_field . '}";', 3);
            $php .= add_tab_endline('$result = $this->conn->query($query );', 3);
            $php .= add_tab_endline('if ($result->num_rows > 0 ){', 3);
                $php .= add_tab_endline('$tmp = $this->update($params );', 4);
            $php .= add_tab_endline('}else{', 3);
                $php .= add_tab_endline('$tmp = $this->create($params );', 4);
            $php .= add_tab_endline('}', 3);
        $php .= add_tab_endline('}', 2);
        $php .= add_tab_endline('return $tmp;', 2);

        $php .= add_tab_endline('}');
        return $php;
    }

    function _model_delete_php($columns ){
        $php = add_tab_endline('public function delete($params ){');
        $php .= add_tab_endline('extract($params );', 2);
        $php .= add_tab_endline('$validate = [];', 2);
        $primary_field = get_primary_field($columns );
        $php .= add_tab_endline('$' . $primary_field . ' = isset($' . $primary_field . ') ? $' .$primary_field . ' : "";', 2);
        $php .= _model_init_primary_where($columns );
        $php .= add_tab_endline('$query = "delete from {$this->table_name} WHERE ' . $primary_field . '={$' . $primary_field . '}";', 2);
        $php .= add_tab_endline('if($this->conn->query($query )){', 2);
            $php .= add_tab_endline('return ["validation"=> $validate, "result"=> true, "affected_rows"=> $this->conn->affected_rows  ];', 3);
        $php .= add_tab_endline('}else{', 2);
            $php .= add_tab_endline('return ["validation"=> $validate, "result"=> $this->conn->error ];', 3);
        $php .= add_tab_endline('}', 2);  

        $php .= add_tab_endline('}' );
        return $php;
    }

    function _model_update_php($columns ){
        $php = add_tab_endline('public function update($params ){');
        $php .= add_tab_endline('extract($params );', 2);
        $php .= add_tab_endline('$validate = [];', 2);
        $php .= _model_init_fields($columns );
        $php .= _model_init_primary_where($columns );
        $php .= _model_init_update($columns );
        $primary_field = get_primary_field($columns );
        $php .= add_tab_endline('$query .= " WHERE ' . $primary_field . '={$' . $primary_field . '}";', 2);
        $php .= add_tab_endline('if($this->conn->query($query )){', 2);
            $php .= add_tab_endline('return ["validation"=> $validate, "result"=> true, "affected_rows"=> $this->conn->affected_rows  ];', 3);
        $php .= add_tab_endline('}else{', 2);
            $php .= add_tab_endline('return ["validation"=> $validate, "result"=> $this->conn->error ];', 3);
        $php .= add_tab_endline('}', 2);  

        $php .= add_tab_endline('}');
        return $php;
    }

    function _model_init_primary_where($columns ){
        $primary_field = get_primary_field($columns );
        $php = "";
        if ($primary_field != "" ){
            $php .= add_tab_endline('if ($' . $primary_field . ' == "" ){', 2);
                $php .= add_tab_endline('return ["validation"=> $validate, "result"=> "primary key not exist" ];', 3);
            $php .= add_tab_endline('}', 2);
        }
        return $php;
    }

    function get_primary_field($columns ){
        $primary_field = $columns[0]->column_name;
        foreach($columns as $key=> $item ){
            if ($item->extra == 'auto_increment'){
                $primary_field = $item->column_name;
                break;
            }
        }
        return $primary_field;
    }

    function _model_create_php($columns ){
        $php = add_tab_endline('public function create($params ){');
        $php .= add_tab_endline('extract($params );', 2);
        $php .= add_tab_endline('$validate = [];', 2 );
        $php .= _model_init_fields($columns );
        $php .= _model_init_required($columns );
        $php .= add_tab_endline('if (count($validate ) > 0 ){', 2);
            $php .= add_tab_endline('return ["validation"=> $validate, "result"=> false ];', 3);
        $php .= add_tab_endline('}', 2);
        $php .= _model_init_insert($columns );
        $php .= add_tab_endline('if($this->conn->query($query )){', 2);
            $php .= add_tab_endline('return ["validation"=> $validate, "result"=> true ];', 3);
        $php .= add_tab_endline('}else{', 2);
            $php .= add_tab_endline('return ["validation"=> $validate, "result"=> $this->conn->error ];', 3);
        $php .= add_tab_endline('}', 2);

        $php .= add_tab_endline('}' );
        return $php;
    }

    function _model_init_insert($columns){
        $php = add_tab_endline('$query = "insert into {$this->table_name } set ";', 2 );
        $comma = ""; 
        $colnames = [];
        foreach($columns as $key=>$item ){
            $column_name = trim($item->column_name);
            if (array_search($column_name, $colnames )){
            }else{
                array_push($colnames, $column_name );
                if ($item->extra != 'auto_increment'){
                    if (strpos($item->data_type, 'int' ) > -1 || strpos($item->data_type, 'float' ) > -1 || strpos($item->data_type, 'double' ) > -1){
                        $php .= add_tab_endline('$query .= $' . $column_name . ' ? "' . $comma . ' ' . $column_name . '={$' . $column_name . '}" : "";', 2);
                    }else{
                        $php .= add_tab_endline('$query .= $' . $column_name . ' ? "' . $comma . ' ' . $column_name . '=' . "'" . '{$' . $column_name . '}' . "'" . '" : "";', 2);
                    }
                    $comma = ",";
                }
            }
        }
        return $php;
    }

    function _model_init_update($columns){
        $php = add_tab_endline('$query = "update {$this->table_name } set ";', 2 );
        $php .= add_tab_endline('$set_arr = [];', 2);
        $comma = ""; 
        $colnames = [];
        foreach($columns as $key=>$item ){
            $column_name = trim($item->column_name);
            if (array_search($column_name, $colnames )){
            }else{
                array_push($colnames, $column_name );
                if ($item->extra != 'auto_increment'){
                    $php .= add_tab_endline('array_push($set_arr, ["field"=> "' . $column_name . '", "value"=> $' . $column_name . ', "type"=> "'. $item->data_type . '"]);', 2);
                }
            }
        }
        $php .= add_tab_endline('$query .= $this->join_set_data($set_arr );', 2);
        return $php;
    }

    function _model_read_php($columns ){
        $php = add_tab_endline('public function read($params ){');
        $php .= add_tab_endline('extract($params );',2 );
        $php .= _model_init_fields($columns, true );

        $php .= add_tab_endline('$page = isset($page) ? $page : "";', 2);
        $php .= add_tab_endline('$page_len = isset($page_len) ? $page_len : "";', 2);

        $php .= add_tab_endline('$query = "select * from {$this->table_name } where 1=1 ";', 2);
        $php .= _model_init_where($columns, true );
        
        $php .= add_tab_endline('$page_limit = "";',2);
        $php .= add_tab_endline('if ($page != ""){',2);
            $php .= add_tab_endline('if ($page_len != "" ){',3);
                $php .= add_tab_endline('$this->page_len = $page_len;',4);
            $php .= add_tab_endline('}',3);
            $php .= add_tab_endline('$start = $this->page_len * ((int)$page - 1 );',3);
            $php .= add_tab_endline('$page_limit =" limit {$start},{$this->page_len}";',3);
        $php .= add_tab_endline('}',2);

        $php .= add_tab_endline('$data = [];', 2);
        $php .= add_tab_endline('$result = $this->conn->query($query );', 2);
		$php .= add_tab_endline('$retrieve_total = $result->num_rows;', 2);
        $php .= add_tab_endline('$query .= $page_limit;', 2);

        $php .= add_tab_endline('$result = $this->conn->query($query );', 2);
        $php .= add_tab_endline('if($result){', 2);
			$php .= add_tab_endline('while ($row = $result->fetch_array()){', 3);
                $php .= _model_join_table($columns );
				$php .= add_tab_endline('array_push($data, $row );', 4);
            $php .= add_tab_endline('}', 3);
        $php .= add_tab_endline('}', 2);
		$php .= add_tab_endline('return ["total"=> $retrieve_total, "data"=> $data ];', 2);

        $php .= add_tab_endline('}');
        return $php;
    }

    function _model_init_fields($columns, $flag = false ){
        $php  = "";
        foreach ($columns as $item ){
            $column_name = trim($item->column_name );
            if (strpos($item->data_type, 'int' ) > -1 || strpos($item->data_type, 'float' ) > -1 || strpos($item->data_type, 'double' ) > -1){
                $php .= add_tab_endline('$' . $column_name  . ' = isset($' . $column_name  . ') ? $' . $column_name  . ': NULL;', 2 );
            }else{
                $php .= add_tab_endline('$' . $column_name  . ' = isset($' . $column_name  . ') ? $' . $column_name  . ': "";', 2 );
            }
            if ($flag == true && ($item->data_type == "date" || $item->data_type == "datetime")){
                $php .= add_tab_endline('$FROM_' . $column_name  . ' = isset($FROM_' . $column_name  . ') ? $FROM_' . $column_name  . ': "";', 2 );
                $php .= add_tab_endline('$TO_' . $column_name  . ' = isset($TO_' . $column_name  . ') ? $TO_' . $column_name  . ': "";', 2 );
            }
        }
        return $php;
    }

    function _model_init_where($columns, $flag = false ){
        $php  = "";
        foreach ($columns as $item ){
            $column_name = trim($item->column_name );

            if ($item->data_type == "date" || $item->data_type == "datetime"){
                $php .= add_tab_endline('if ($FROM_' . $column_name . ' != ""){', 2 );
                $php .= add_tab_endline('$query .= " and DATE(' . $column_name . ' >= DATE(' . "'" . '{$FROM_' . $column_name . '}' . "')" . '";', 3 );
                $php .= add_tab_endline("}", 2);
                $php .= add_tab_endline('if ($TO_' . $column_name . ' != ""){', 2 );
                $php .= add_tab_endline('$query .= " and DATE(' . $column_name . ' >= DATE(' . "'" . '{$TO_' . $column_name . '}' . "')" .'";', 3 );
                $php .= add_tab_endline("}", 2);
            }else{
                $php .= add_tab_endline('if ($' . $column_name . ' != ""){', 2 );
                $php .= add_tab_endline('$query .= " and ' . $column_name . '={$' . $column_name . '}";', 3 );
                $php .= add_tab_endline("}", 2);
            }
        }
        return $php;
    }

    function _model_init_required($columns ){
        $php = "";
        foreach ($columns as $key=>$item ){
            $column_name = trim($item->column_name );
            if ($item->extra != 'auto_increment' && $item->is_nullable == 'NO'){
                $php .= add_tab_endline('if ($' . $column_name . ' == ""){', 2 );
                $php .= add_tab_endline('$validate["' . $column_name . '"] = "required";', 3);
                $php .= add_tab_endline('}', 2);
            }
        }
        return $php;
    }

    function _model_join_table($columns ){
        $php = "";
        foreach($columns as $item ){
            $ref_table_name = $item->referenced_table_name;
            if ($ref_table_name != "" ){
                $column_name = $item->column_name;
                $ref_column_name = $item->referenced_column_name;
                $php .= add_tab_endline('$row["' . $ref_table_name . '"] = $this->table_join_data("' . $ref_table_name . '", "' . $ref_column_name . '", $row["' . $column_name . '"]);', 4 );
            }
        }
        return $php;
    }

    function add_tab_endline($str , $tab_len = 1, $endline_len = 1){
        for($i = 0; $i < $tab_len; $i++ ){
            $str = "\t" . $str;
        }

        for($i = 0; $i < $endline_len; $i++ ){
            $str = $str . "\n";
        }
        return $str;
    }

    function rrmdir($dir) { 
        if (is_dir($dir)) { 
          $objects = scandir($dir); 
          foreach ($objects as $object) { 
            if ($object != "." && $object != "..") { 
              if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
            } 
          } 
          reset($objects); 
          rmdir($dir); 
        } 
    }

    function dashesToCamelCase($string, $capitalizeFirstCharacter = true) {
        $str = str_replace('_', '', ucwords($string, '_'));
        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }
        return $str;
    }
?>
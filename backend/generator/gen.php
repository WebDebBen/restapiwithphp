<?php
    function generate_content($data, $type ){
        $result = ["status"=> "success", "data"=> ""];
        switch($type ){
            case "sql":
                $result["data"] = generate_content_sql($data );
                break;
            case "html":
                $result["data"] = generate_content_html($data ); 
                break;
            case "javascript":
                $result["data"] = generate_content_javascript($data ); 
                break;
            case "php":
                $result["data"] = generate_content_php($data ); 
                break;
            case "run":
                $result = run_content($data );
                break;
        } 
        return $result;
    }

    function run_content($data ){
        $table_name = $data["table_name"];

        $sql = generate_content_sql($data, false );
        $html = generate_content_html($data );
        $js = generate_content_javascript($data );
        $php = generate_content_php($data );

        $folder_name = $table_name;
        $folder_path = "../result/" . $folder_name;
        if (file_exists($folder_path )){
            return ["status"=> "duplicated", "data"=> ""];
        }else{
            mkdir($folder_path, 0700);
        }
        $sql_file = $table_name . "_sql.php";
        $sql_path = $folder_path . "/" . $sql_file;
        $html_file = "index.php";
        $html_path = $folder_path . "/" . $html_file;
        $php_file = $table_name . ".php";
        $php_path = $folder_path . "/" .  $php_file;
        $js_file = "table_" . $table_name . ".js";
        $js_path = "../result/assets/js/" . $js_file;

        $myfile = fopen($sql_path, "w") or die("Unable to open file!");
        fwrite($myfile, $sql );
        fclose($myfile);

        $myfile = fopen($html_path, "w") or die("Unable to open file!");
        fwrite($myfile, $html );
        fclose($myfile);

        $myfile = fopen($php_path, "w") or die("Unable to open file!");
        fwrite($myfile, $php );
        fclose($myfile);

        $myfile = fopen($js_path, "w") or die("Unable to open file!");
        fwrite($myfile, $js );
        fclose($myfile);

        return ["status"=> "success", "data"=> SERVER_URL . $table_name];
    }

    function generate_content_sql($data, $flag = true ){
        $table_name = $data["table_name"];
        $primary_key = $data["primary_key"];
        $columns = $data["columns"];
        $endln = $flag ? "\n" : "";
        $tab = $flag ? "\t" : "";

        $php_str = $flag ? "": '<?php $query = "';
        $php_str_end = $flag ? "" : '" ?>';
        $str = $php_str . "CREATE TABLE IF NOT EXISTS `{$table_name}`({$endln}{$tab}`{$primary_key}` int(10) NOT NULL auto_increment";
        foreach($columns as $col ){
            extract($col );
            $field_item = "`{$title}` {$type}";
            if ($requried ){
                $field_item .= " NOT NULL";
            }else{
                $field_item .= " NULL";
            }
            if ($default_value ){
                $field_item .= " DEFAULT {$default_value}";
            }
            $str .= ", {$endln}{$tab} {$field_item }";
        }
        $str .= ",{$endln}{$tab}PRIMARY KEY(`{$primary_key}`){$endln});";
        $str .= $php_str_end;
        return $str;
    }

    function generate_content_html($data, $flag = true ){
        $table_name = $data["table_name"];
        $primary_key = $data["primary_key"];
        $columns = $data["columns"];

        $header = '<!doctype html><html>';
        $header .= '<head><meta http-equiv="content-type" content="text/html; charset=utf-8" />';
            $header .= "<title>DataTables Editor - {$table_name}</title>";
            $header .= '<link rel="stylesheet" type="text/css" href="/backend/result/assets/css/bootstrap.min.css">';
            $header .= '<link rel="stylesheet" type="text/css" href="/backend/result/assets/font-awesome/css/font-awesome.css">';
            $header .= '<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">';
            $header .= '<link rel="stylesheet" type="text/css" href="/backend/result/assets/css/style.css">';
            $header .= '<script type="text/javascript" src="/backend/result/assets/js/jquery-2.2.4.js"></script>';
            $header .= '<script type="text/javascript" src="/backend/result/assets/js/bootstrap.min.js"></script>';
            $header .= '<script type="text/javascript" src="/backend/result/assets/js/jquery.validate.min.js"></script>';
            $header .= '<script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>';
            $header .= '<script type="text/javascript" charset="utf-8" src="/backend/result/assets/js/table_' . $table_name . '.js"></script>';
        $header .= '</head>';

        $body = '<body class="dataTables">';
            $body .= "<div class='container'>";
                $body .= '<h1 class="mt-1r">DataTables Editor <span>' . $table_name . '</span></h1>';
                $body .= '<div class="row mt-1r mb-1r"><div class="col-md-12"><button class="btn btn-success" id="' . $table_name . '_new">New</button></div></div>';
                $body .= '<div class="row mt-2r">';
                    $body .= '<div class="col-md-12">';
                        $body .= '<table cellpadding="0" cellspacing="0" border="0" class="display" id="' . $table_name . '_table" width="100%">'; 
                            $body .= '<thead><tr>';
                                foreach($columns as $col ){
                                    $body .= "<th>{$col['title']}</th>";
                                }
                                $body .= "<th>Action</th>";
                            $body .= '</tr></thead>';
                            $body .= "<tbody id='" . $table_name . "_body'>";
                            $body .= "</tbody>";
                        $body .= '</table>';
                    $body .= '</div>';
                $body .= "</div>";
            $body .= "</div>";

            // modal div
            $body .= '<div class="modal column-detail-modal" tabindex="-1" role="dialog" id="edit-modal">';
                $body .= '<div class="modal-dialog" role="document">';
                    $body .= '<div class="modal-content">';
                        $body .= '<div class="modal-header">';
                            $body .= '<h5 class="modal-title">' . $table_name . ' Table</h5>';
                            $body .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                                $body .= '<span aria-hidden="true">&times;</span>';
                            $body .= '</button>';
                        $body .= '</div>';

                        $body .= '<form class="form">';
                            $body .= '<div class="modal-body">';
                                foreach($columns as $col ){
                                    extract($col );
                                    $body .= '<div class="form-group row">';
                                        $body .= '<label for="field-default-value" class="col-sm-4 col-form-label text-right">' . $title . '</label>';
                                        $body .= '<div class="col-sm-8">';
                                            if ($type == 'boolean'){
                                                $body .= '<input type="checkbox" class="form-control" data-type="checkbox" id="' . $table_name . '_field_' . $title . '">';
                                            }else if($type == 'date' || $type == 'datetime'){
                                                $body .= '<input type="text" class="form-control" data-type="date" id="' . $table_name . '_field_' . $title . '">';
                                            }else{
                                                $body .= '<input type="text" class="form-control" data-type="string" id="' . $table_name . '_field_' . $title . '">';
                                            }
                                        $body .= '</div>';
                                    $body .= '</div>';
                                }
                            $body .= '</div>';
                        $body .= '</form>';

                        $body .= '<div class="modal-footer">';
                            $body .= '<input type="hidden" id="data-id" value="-1"/>';
                            $body .= '<button type="button" class="btn btn-primary" id="save_record">Save</button>';
                            $body .= '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
                        $body .= '</div>';
                    $body .= '</div>';
                $body .= '</div>';
            $body .= '</div>';
        $body .= "</body>";

        $footer = '</body></html>';

        $html = $header.$body.$footer;
        return $html;

        // $table_name = $data["table_name"];
        // $primary_key = $data["primary_key"];
        // $columns = $data["columns"];

        // $header = '<!doctype html><html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><title>DataTables Editor - ' 
        //     . $table_name . '</title><link rel="stylesheet" type="text/css" href="/backend/result/assets/css/datatables.min.css"><link rel="stylesheet" type="text/css" href="/backend/result/assets/css/style.css"><link rel="stylesheet" type="text/css" href="/backend/result/assets/css/editor.dataTables.min.css"><script type="text/javascript" charset="utf-8" src="/backend/result/assets/js/datatables.min.js"></script><script type="text/javascript" charset="utf-8" src="/backend/result/assets/js/dataTables.editor.min.js"></script><script type="text/javascript" charset="utf-8" src="/backend/result/assets/js/table.' . $table_name . '.js"></script></head><body class="dataTables">';
        // $footer = '</body></html>';
        
        // $html = $header;
        // $html .= "<div class='container'>";
        //     $html .= "<h1>DataTables Editor <span>" . $table_name . "</sapn></h1>";
        //     $html .= '<table cellpadding="0" cellspacing="0" border="0" class="display" id="' . $table_name . '" width="100%">';
        //         $html .= '<thead><tr>';
        //             foreach($columns as $col ){
        //                 $html .= "<th>" . $col["title"] . "</th>";
        //             }
        //         $html .= '</tr></thead>';
        //     $html .= '</table>';
        // $html .= "</div>";
        // $html .= $footer;  
        // return $html;
    }

    function generate_content_javascript($data, $flag = true ){
        $endln = $flag ? "\n" : "";
        $tab = $flag ? "\t" : "";

        $table_name = $data["table_name"];
        $primary_key = $data["primary_key"];
        $columns = $data["columns"];

        $js = 'base_url = "/backend/result/' . $table_name . '/' . $table_name . '.php";';
        $js .= $endln . 'var table;';
        $js .= $endln . 'var sel_tr;';
        $js .= $endln . '$(document).ready(function(){';
            $js .= $endln . $tab . 'init_table();';
            $js .= $endln . $tab . '$("#' . $table_name . '_new").on("click", new_record);';
            $js .= $endln . $tab . '$("#' . $table_name . '_body").on("click", ".edit-item", edit_record );';
            $js .= $endln . $tab . '$("#' . $table_name . '_body").on("click", ".delete-item", delete_record );';
            $js .= $endln . $tab . '$("#save_record").on("click", save_record );';
        $js .= $endln . '});';

        $js .= $endln . 'function save_record(){';
            $js .= $endln . $tab . 'var id = $("#data-id").val();';
            foreach($columns as $col ){
                extract($col );
                $js .= $endln . $tab . 'var tr_' . $title . ' = $("#' . $table_name . '_field_' . $title . '").val()';
            }
            $js .= $endln . $tab . '$.ajax({';
                $js .= $endln . $tab . $tab . 'url: base_url,';
                $js .= $endln . $tab . $tab . 'data:{';
                    $js .= $endln . $tab . $tab . $tab . 'type: "save",';
                    $js .= $endln . $tab . $tab . $tab . 'id: id,';
                    foreach($columns as $col ){
                        extract($col );
                        $js .= $endln . $tab . $tab . $tab . $title . ': tr_' . $title . ",";
                    }
                $js .= $endln . $tab . $tab . '},';
                $js .= $endln . $tab . $tab . 'type: "post",';
                $js .= $endln . $tab . $tab . 'dataType: "json",';
                $js .= $endln . $tab . $tab . 'success: function(data){';
                    $js .= $endln . $tab . $tab . $tab . 'if (data["status"] == "success" ){';
                        $js .= $endln . $tab . $tab . $tab . $tab . 'if (id == "-1"){';
                            $js .= $endln . $tab . $tab . $tab . $tab . $tab . 'var table_id = data["insert_id"];';
                            $js .= $endln . $tab . $tab . $tab . $tab . $tab . 'table.row.add( [';
                            foreach($columns as $col ){
                                extract($col );
                                $js .= 'tr_' . $title . ', ';
                            }
                            $js .= "'" . '<button class="btn btn-xs btn-sm btn-primary mr-6 edit-item" data-id="' .
                            "table_id" . '"><i class="fa fa-edit"></i></button><button class="btn btn-xs btn-sm btn-secondary delete-item" data-id"' . "table_id" . '"><i class="fa fa-trash"></i></button>' . "'" . ']).draw( false );';
                        $js .= $endln . $tab . $tab . $tab . $tab . '}else{';
                            foreach($columns as $col ){
                                extract($col );
                                $js .= $endln . $tab . $tab . $tab . $tab . $tab . '$("#' . $table_name . '_table tr.selected").find(".' . $table_name. '_td_' . $title . '").text(tr_' . $title . ' );';
                            }
                        $js .= $endln . $tab . $tab . $tab . $tab . '}';
                        $js .= $endln . $tab . $tab . $tab . $tab . '$("#edit-modal").modal("hide");';
                    $js .= $endln . $tab . $tab . $tab . '}';
                $js .= $endln . $tab . $tab . '}';

            $js .= $endln . $tab . '});';
        $js .= $endln . '}';

        $js .= $endln . 'function new_record(){';
            $js .= $endln . $tab . '$("#data-id").val("-1");';
            $js .= $endln . $tab . '$("#edit-modal").modal("show");';
        $js .= $endln . '}';

        $js .= $endln . 'function delete_record(){';
            $js .= $endln . $tab . 'var id = $(this).attr("data-id");';
            $js .= $endln . $tab . 'sel_tr = $(this).parent().parent();';
            $js .= $endln . $tab . 'if (confirm("Are you going to delete this record?")){';
                $js .= $endln . $tab . $tab . '$.ajax({';
                    $js .= $endln . $tab . $tab . $tab . 'url: base_url,';
                    $js .= $endln . $tab . $tab . $tab . 'data:{';
                        $js .= $endln . $tab . $tab . $tab . $tab . "type: 'delete',";
                        $js .= $endln . $tab . $tab . $tab . $tab . "id: id";
                    $js .= $endln . $tab . $tab . $tab . '},';
                    $js .= $endln . $tab . $tab . $tab . 'type:"post",';
                    $js .= $endln . $tab . $tab . $tab . 'dataType: "json",';
                    $js .= $endln . $tab . $tab . $tab . 'success: function(data){';
                        $js .= $endln . $tab . $tab . $tab . $tab . 'if (data["status"] == "success"){';
                            $js .= $endln . $tab . $tab . $tab . $tab . $tab . "table.row('.selected').remove().draw( false );";
                        $js .= $endln . $tab . $tab . $tab . $tab . '}';
                    $js .= $endln . $tab.  $tab . $tab . '}';
                $js .= $endln . $tab . $tab . '})';
            $js .= $endln . $tab . '}';
        $js .= $endln . '}';

        $js .= $endln . 'function edit_record(){';
            $js .= $endln . $tab . "var id = $(this).attr('data-id');";
            $js .= $endln . $tab . "sel_tr = $(this).parent().parent();";
            $js .= $endln . $tab . '$("#data-id").val(id );';
            foreach($columns as $col ){
                extract($col );
                $js .= $endln . $tab . $tab . '$("#' . $table_name . '_field_' . $title . '").val($(sel_tr).find(".' . $table_name . '_td_' . $title . '").text());';
            }
            $js .= '$("#edit-modal").modal("show");';
        $js .= $endln . '}';

        $js .= $endln . 'function init_table(){';
            $js .= $endln . $tab . '$.ajax({';
                $js .= $endln . $tab . $tab . 'url: base_url,';
                $js .= $endln . $tab . $tab . 'data:{';
                    $js .= $endln . $tab . $tab . $tab . 'type: "init_table"';
                $js .= $endln . $tab . $tab . '},';
                $js .= $endln . $tab . $tab . 'dataType: "json",';
                $js .= $endln . $tab . $tab . 'type: "post",';
                $js .= $endln . $tab . $tab . 'success: function(data ){';
                    $js .= $endln . $tab . $tab . $tab . 'if (data["status"] == "success" ){';
                        $js .= $endln . $tab . $tab . $tab . $tab . 'load_data(data["data"]);';
                    $js .= $endln . $tab . $tab . $tab . '}';
                $js .= $endln . $tab . $tab . '}';
            $js .= $endln . $tab . '});';
        $js .= $endln . '}';

        $js .= $endln . 'function load_data(data ){';
            $js .= $endln . $tab . 'var parent = $("#' . $table_name . '_body");';
            $js .= $endln . $tab . 'for(var i = 0; i < data.length; i++ ){';
                $js .= $endln . $tab . $tab . 'var item = data[i];';
                $js .= $endln . $tab . $tab . "var tr = $('<tr>').attr('data-id', item[0]).appendTo(parent );";
                foreach($columns as $key=> $col ){
                    extract($col );
                    $js .= $endln . $tab . $tab . $tab . '$("<td>").text(item[' . ($key + 1) . ']).addClass("' . $table_name . '_td_' . $title . '").appendTo(tr);';
                }
                $js .= $endln . $tab . $tab . 'var td = $("<td>").appendTo(tr );';
                $js .= $endln . $tab . $tab . '$("<button>").addClass("btn btn-xs btn-sm btn-primary mr-6 edit-item")';
                            $js .= $endln . $tab . $tab.  $tab . '.attr("data-id", item[0])';
                            $js .= $endln . $tab . $tab.  $tab . '.html("<i class=' . "'fa fa-edit'" . '></i>").appendTo(td );';
                $js .= $endln . $tab . $tab . '$("<button>").addClass("btn btn-xs btn-sm btn-secondary delete-item")';
                            $js .= $endln . $tab . $tab.  $tab . '.attr("data-id", item[0])';
                            $js .= $endln . $tab . $tab.  $tab . '.html("<i class=' . "'fa fa-trash'" . '></i>").appendTo(td );';
            $js .= $endln . $tab . '}';
            $js .= $endln . $tab . 'table = $("#' . $table_name . '_table").DataTable();';
        
            $js .= $endln . $tab . "$('#" . $table_name . "_table tbody').on( 'click', 'tr', function () {";
                $js .= $endln . $tab . $tab . "if ( $(this).hasClass('selected') ) {";
                    $js .= $endln . $tab . $tab . $tab . "$(this).removeClass('selected');";
                $js .= $endln . $tab . $tab . "}";
                $js .= $endln . $tab . $tab . "else {";
                    $js .= $endln . $tab . $tab . "table.$('tr.selected').removeClass('selected');";
                    $js .= $endln . $tab . $tab . "$(this).addClass('selected');";
                $js .= $endln . $tab . $tab . "}";
            $js .= $endln . $tab . "});";
        $js .= $endln . "}"; 
        return $js;
        // $javascript = '(function($){$(document).ready(function() {';
        //     $javascript .= $endln. $tab . $tab . 'var editor = new $.fn.dataTable.Editor( {';
        //         $javascript .= $endln. $tab . $tab . $tab . "ajax: 'php/table." . $table_name . ".php',";
        //         $javascript .= $endln. $tab . $tab . $tab . "table: '#" . $table_name . "',";
        //         $javascript .= $endln. $tab . $tab . $tab . "fields: [";
        //             foreach($columns as $col ){
        //                 $javascript .= $endln. $tab . $tab . $tab . $tab . '{"label": "' . $col["title"] . ':","name": "' . $col["title"] . '"}';
        //             }
        //         $javascript .= $endln. $tab . $tab . $tab . "]";
        //     $javascript .= $endln. $tab . $tab . '});';

        //     $javascript .= $endln. $tab .$tab .  "var table = $('#test').DataTable( {";
        //         $javascript .= $endln. $tab . $tab . $tab . "dom: 'Bfrtip',";
        //         $javascript .= $endln. $tab . $tab . $tab . "ajax: 'php/table.test.php',";
        //         $javascript .= $endln. $tab . $tab . $tab . "columns: [";
        //             foreach($columns as $col ){
        //                 $javascript .= $endln. $tab . $tab . $tab . $tab . '{"data": "' . $col["title"] . '"},';
        //             }
        //         $javascript .= $endln. $tab . $tab . $tab . "],";
        //         $javascript .= $endln. $tab . $tab . $tab . "select: true,";
        //         $javascript .= $endln. $tab . $tab . $tab . "lengthChange: false,";
        //         $javascript .= $endln. $tab . $tab . $tab . "buttons: [";
        //             $javascript .= $endln. $tab . $tab . $tab . $tab . "{ extend: 'create', editor: editor },";
        //             $javascript .= $endln. $tab . $tab . $tab . $tab . "{ extend: 'edit',   editor: editor },";
        //             $javascript .= $endln. $tab . $tab . $tab . $tab . "{ extend: 'remove', editor: editor }";
        //         $javascript .= $endln. $tab . $tab . $tab . "]";
        //     $javascript .= $endln. $tab . $tab . $tab . "});";
        // $javascript .= $endln . '});}(jQuery));';
        // return $javascript;
    }

    function generate_content_php($data, $flag = true ){
        $endln = $flag ? "\n" : "";
        $tab = $flag ? "\t" : "";

        $table_name = $data["table_name"];
        $primary_key = $data["primary_key"];
        $columns = $data["columns"];

        $tmp = "";
        $tmp_key = "";
        foreach($columns as $key=> $col ){
            extract($col);
            if ($key == 0 ){
                $tmp .= $title . "='" .'{$' . $title . "}'";
                $tmp_key .= "$" . $title;
            }else{
                $tmp .= "," . $title . "='" . '{$' . $title . "}'";
                $tmp_key .= "," . "$" . $title;
            }
        }

        $php  = '<?php';
            $php .= $endln . $tab .  'include_once("../../config.php");';
            $php .= $endln . $tab .  'include_once("./' . $table_name . '_sql.php");';
            $php .= $endln . $tab .  'include_once("../db.php");';
            $php .= $endln . $tab .  '$db = new dbObj();';
            $php .= $endln . $tab .  'extract($_POST);';
            $php .= $endln . $tab .  'switch($type ){';
                $php .= $endln . $tab .  $tab . 'case "init_table":';
                    $php .= $endln . $tab .  $tab . $tab . 'init_table();';
                    $php .= $endln . $tab .  $tab . $tab . 'break;';
                $php .= $endln . $tab. $tab .  'case "delete":';
                    $php .= $endln . $tab .  $tab . $tab . 'delete_tr($id);';
                    $php .= $endln . $tab .  $tab . $tab . 'break;';
                $php .= $endln . $tab. $tab .  'case "save":';
                    $php .= $endln . $tab .  $tab .  $tab .  'save_tr($id ';
                        foreach($columns as $col ){
                            extract($col );
                            $php .= ",$" . $title;
                        }
                    $php .= ');';
                    $php .= $endln . $tab. $tab .  $tab . 'break;';
            $php .= $endln . $tab . '}';
            
            $php .= $endln . 'function init_table(){';
                $php .= $endln . $tab . '$query = $GLOBALS["query"];';
                $php .= $endln . $tab . '$db = $GLOBALS["db"];';
                $php .= $endln . $tab . '$db->run_query($query );';
                $php .= $endln . $tab . '$result = $db->load_data("' . $table_name . '");';
        
                $php .= $endln . $tab . '$data = [];';
                $php .= $endln . $tab . 'if($result){';
                    $php .= $endln . $tab . $tab . 'while ($row = $result->fetch_array()){';
                        $php .= $endln . $tab . $tab . $tab . '$item = [];';
                        $php .= $endln . $tab . $tab . $tab . 'array_push($item, $row["id"]);';
                        foreach($columns as $col ){
                            extract($col );
                            $php .= $endln . $tab . $tab . $tab . 'array_push($item, $row["' . $title . '"]);';
                        }
                        $php .= $endln . $tab . $tab . $tab . 'array_push($data, $item );';
                    $php .= $endln . $tab . $tab . '}';
                $php .= $endln . $tab . '}';
                $php .= $endln . $tab . 'echo json_encode(["status"=>"success", "data"=> $data ]);';
            $php .= $endln . '}';

            $php .= $endln . 'function delete_tr($id ){';
                $php .= $endln . $tab . '$query = "delete from ' . $table_name . ' where ' . $primary_key . '={$id}";';
                $php .= $endln . $tab . '$db = $GLOBALS["db"];';
                $php .= $endln . $tab . '$db->run_query($query );';
                $php .= $endln . $tab . 'echo json_encode(["status"=> "success"]);';
            $php .= $endln . '}';

            $php .= $endln . 'function save_tr($id, ' . $tmp_key . '){';
                $php .= $endln . $tab . '$db = $GLOBALS["db"];';
                $php .= $endln . $tab . 'if ($id == "-1"){';
                    $php .= $endln . $tab . $tab . '$query = "insert into ' . $table_name . ' set ' . $tmp . '";';
                $php .= $endln . $tab . '}else{';
                    $php .= $endln . $tab . $tab . '$query = "update ' . $table_name . ' set ' . $tmp . ' where ' . $primary_key . '={$id}";';
                $php .= $endln . $tab . '}';
                $php .= $endln . $tab . '$id = $db->update_query($query );';
                $php .= $endln . $tab . 'echo json_encode(["status"=> "success", "' . $primary_key . '"=> $id ]);';
            $php .= $endln . '}';

        $php .= $endln . "?>";
        return $php;
    }
?>
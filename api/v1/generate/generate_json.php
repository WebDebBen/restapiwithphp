<?php
    function generate_json($table_info ){
        $api_obj = [];
        $api_obj["swagger"] = '2.0';
        $api_obj["info"] = [
            "description"=> "This is Rest API Documentation",
            "version"=> "1.0.0",
            "contact"=> [
                "email"=> "api@api.com"
            ],
            "title"=> "Rest API",
            "license"=> [
                "name"=> "API Generate",
                "url"=> "http://localhost"
            ]
        ];
        $api_obj["host"] = "localhost.com";
        $api_obj["basePath"] = "/api/v1";

        $api_obj["tags"] = get_tags_info($table_info );
        $api_obj["paths"] = get_path_info($table_info );
        $api_obj["definitions"] = get_definition_info($table_info );

        return $api_obj;
    }

    function get_tags_info($table_info ){
        $tags = [];
        foreach($table_info as $item ){
            $tag = [
                "name"=> $item->table_name,
                "description"=> $item->table_name . " API"
            ];
            array_push($tags, $tag );
        }
        return $tags;
    }

    function get_path_info($table_info ){
        $paths_obj = [];
        foreach($table_info as $item ){
            $paths_obj['/' . trim($item->table_name) . '/read'] = get_read_path_info($item );
            $paths_obj['/' . trim($item->table_name) . '/create'] = get_create_path_info($item );
            $paths_obj['/' . trim($item->table_name) . '/update'] = get_update_path_info($item );
            $paths_obj['/' . trim($item->table_name) . '/delete'] = get_delete_path_info($item );
            $paths_obj['/' . trim($item->table_name) . '/addupdate'] = get_addupdate_path_info($item );
        }
        return $paths_obj;
    }

    
    function get_delete_path_info($info ){
        $columns = $info->columns;
        $primary_field = get_primary_field($columns );

        $tb_name = trim($info->table_name );
        $tb_camel_name = dashesToCamelCase($tb_name );
        $obj = [];
        $obj["delete"]= [
            "tags"=> [$tb_name ],
            "summary"=> "Delete " . $tb_camel_name,
            "description"=> "Delete " . $tb_camel_name,
            "operationId"=> "delete" . $tb_camel_name,
            "consumes"=> ["application/x-www-form-urlencoded"],
            "produces"=> ["application/json", "application/xml"],
            "parameters"=> [
                ["name"=> $primary_field,
                "in"=> "formData",
                "required"=> true,
                "type"=> "integer",
                "format"=> "int64"]
            ],
            "responses"=> [
                "200"=> [
                    "description"=> "Success"
                ],
                "400"=> [
                    "description"=> "Invalid Parameters"
                ]
            ]
        ];
        return $obj; 
    }

    function get_addupdate_path_info($info ){
        $columns = $info->columns;
        $params = [];
        $cols = [];
        foreach($columns as $col ){
            if (!array_search(trim($col->column_name), $cols)){
                $tmp = [
                    "name"=> trim($col->column_name),
                    "in"=> "formData",
                    "description"=> trim($col->column_name),
                    "required"=> $col->is_nullable == 'YES' ? false : true,
                ];
                if ($col->referenced_table_name ){
                    //$tmp["schema"] = ['$ref' => "#/definitions/" .dashesToCamelCase($col->referenced_table_name)];
                    $tmp["type"] = "string";
                }else{
                    $field_type = get_field_type($col );
                    $tmp["type"] = $field_type["type"];
                }
                array_push($params, $tmp );
                array_push($cols, trim($col->column_name));
            }
        }

        $tb_name = trim($info->table_name );
        $tb_camel_name = dashesToCamelCase($tb_name );
        $obj = [];
        $obj["post"]= [
            "tags"=> [$tb_name ],
            "summary"=> "AddUpdate " . $tb_camel_name,
            "description"=> "AddUpdate " . $tb_camel_name,
            "operationId"=> "addupdate" . $tb_camel_name,
            "consumes"=> ["application/x-www-form-urlencoded"],
            "produces"=> ["application/json", "application/xml"],
            "parameters"=> $params,
            "responses"=> [
                "200"=> [
                    "description"=> "Success"
                ],
                "400"=> [
                    "description"=> "Invalid Parameters"
                ]
            ]
        ];
        return $obj; 
    }

    function get_update_path_info($info ){
        $columns = $info->columns;
        $params = [];
        $cols = [];
        foreach($columns as $col ){
            if (!array_search(trim($col->column_name), $cols)){
                $tmp = [
                    "name"=> trim($col->column_name),
                    "in"=> "formData",
                    "description"=> trim($col->column_name),
                    "required"=> $col->is_nullable == 'YES' ? false : true,
                ];
                if ($col->referenced_table_name ){
                    //$tmp["schema"] = ['$ref' => "#/definitions/" .dashesToCamelCase($col->referenced_table_name)];
                    $tmp["type"] = "string";
                }else{
                    $field_type = get_field_type($col );
                    $tmp["type"] = $field_type["type"];
                }
                array_push($params, $tmp );
                array_push($cols, trim($col->column_name));
            }
        }

        $tb_name = trim($info->table_name );
        $tb_camel_name = dashesToCamelCase($tb_name );
        $obj = [];
        $obj["put"]= [
            "tags"=> [$tb_name ],
            "summary"=> "Update " . $tb_camel_name,
            "description"=> "Update " . $tb_camel_name,
            "operationId"=> "update" . $tb_camel_name,
            "consumes"=> ["application/x-www-form-urlencoded"],
            "produces"=> ["application/json", "application/xml"],
            "parameters"=> $params,
            "responses"=> [
                "200"=> [
                    "description"=> "Success"
                ],
                "400"=> [
                    "description"=> "Invalid Parameters"
                ]
            ]
        ];
        return $obj; 
    }

    function get_create_path_info($info ){
        $columns = $info->columns;
        $params = [];
        $cols = [];
        foreach($columns as $col ){
            if (!array_search(trim($col->column_name), $cols) && $col->extra != 'auto_increment' ){
                $tmp = [
                    "name"=> trim($col->column_name),
                    "in"=> "formData",
                    "description"=> trim($col->column_name),
                    "required"=> $col->is_nullable == 'YES' ? false : true,
                ];
                if ($col->referenced_table_name ){
                    //$tmp["schema"] = ['$ref' => "#/definitions/" .dashesToCamelCase($col->referenced_table_name)];
                    $tmp["type"] = "string";
                }else{
                    $field_type = get_field_type($col );
                    $tmp["type"] = $field_type["type"];
                }
                array_push($params, $tmp );
                array_push($cols, trim($col->column_name));
            }
        }

        $tb_name = trim($info->table_name );
        $tb_camel_name = dashesToCamelCase($tb_name );
        $obj = [];
        $obj["post"]= [
            "tags"=> [$tb_name ],
            "summary"=> "Create " . $tb_camel_name,
            "description"=> "Create " . $tb_camel_name,
            "operationId"=> "create" . $tb_camel_name,
            "consumes"=> ["application/x-www-form-urlencoded"],
            "produces"=> ["application/json", "application/xml"],
            "parameters"=> $params,
            "responses"=> [
                "200"=> [
                    "description"=> "Success"
                ],
                "400"=> [
                    "description"=> "Invalid Parameters"
                ]
            ]
        ];
        return $obj; 
    }

    function get_read_path_info($info ){
        $columns = $info->columns;
        $params = [];
        $cols = [];
        foreach($columns as $col ){
            if (!array_search(trim($col->column_name), $cols)){
                $tmp = [
                    "name"=> trim($col->column_name),
                    "in"=> "query",
                    "description"=> trim($col->column_name),
                    "required"=> $col->is_nullable == 'YES' ? false : true,
                ];
                if ($col->referenced_table_name ){
                    //$tmp["schema"] = ['$ref' => "#/definitions/" .dashesToCamelCase($col->referenced_table_name)];
                    $tmp["type"] = "string";
                }else{
                    $field_type = get_field_type($col );
                    $tmp["type"] = $field_type["type"];
                }
                array_push($params, $tmp );
                array_push($cols, trim($col->column_name));
            }
        }

        $tb_name = trim($info->table_name );
        $tb_camel_name = dashesToCamelCase($tb_name );
        $obj = [];
        $obj["get"]= [
            "tags"=> [$tb_name ],
            "summary"=> "Read " . $tb_camel_name,
            "description"=> "Read " . $tb_camel_name,
            "operationId"=> "read" . $tb_camel_name,
            "consumes"=> ["application/json"],
            "produces"=> ["application/json", "application/xml"],
            "parameters"=> $params,
            "responses"=> [
                "200"=> [
                    "description"=> "Success"
                ],
                "400"=> [
                    "description"=> "Invalid Parameters"
                ]
            ]
        ];
        return $obj;
    }

    function get_definition_info($table_info ){
        $define_obj = [];
        foreach($table_info as $item ){
            $obj = [];
            $model_name = dashesToCamelCase(trim($item->table_name ));
            $obj["type"] = "object";
            $prop_obj = [];
            foreach($item->columns as $column ){
                $prop_obj[trim($column->column_name)] = get_field_type($column);
            }
            $obj["properties"] = $prop_obj;
            $define_obj[$model_name] = $obj;
        }
        return $define_obj;
    }

    function get_field_type($column ){
        $field = $column->data_type;
        $ref_table = $column->referenced_table_name;
        $column_type = $column->column_type;

        if ($ref_table ){
            return ['$ref'=>"#/definitions/" . dashesToCamelCase($ref_table)];
        }

        $type = ["type"=>"integer"];
        $field = strtolower($field );
        switch($field ){
            case 'float': case 'double':case 'numeric': case 'decimal':case 'smallmoney': case 'money': case 'real': case 'year':
                $type["type"] = "number";
                $type["format"] = $field;
                break;
            case 'tinyint': case 'smallint': case 'int': case 'bigint': 
                $type["type"] = "integer";
                $type["format"] = $field;
                break;
            case 'enum': case 'set':
                $column_type = str_replace("'", "", $column_type );
                $c_arr = explode("(", $column_type );
                $c_arr = explode(")", $c_arr[1]);
                $c_arr = $c_arr[0];
                $c_arr = explode(",", $c_arr );
                $type["type"] = "string";
                $type["enum"] = $c_arr;
                $type["default"] = $c_arr[0];
                break;
            default:
                $type["type"] = "string";
                $type["format"] = $field;
                break;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
        }

        return $type;
    }
?>
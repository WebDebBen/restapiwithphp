<?php
class TbaccountsGroupsMembers{
    private $conn;
    private $table_name = "tbaccounts_groups_members";
    private $page_len = 10;

    public function __construct($db ){
        $this->conn = $db;
    }

    public function read($params ){
        extract($params );
        $CLMGROUP_MEMBER_ID = isset($CLMGROUP_MEMBER_ID) ? $CLMGROUP_MEMBER_ID : "";
        $CLMGROUP_MEMBER_GROUPID = isset($CLMGROUP_MEMBER_GROUPID) ? $CLMGROUP_MEMBER_GROUPID : "";
        $CLMGROUP_MEMBER_USERID = isset($CLMGROUP_MEMBER_USERID) ? $CLMGROUP_MEMBER_USERID : "";
        $CLMGROUP_MEMBER_USERTYPE = isset($CLMGROUP_MEMBER_USERTYPE) ? $CLMGROUP_MEMBER_USERTYPE : "";
        $CLMGROUP_MEMBER_CREATOR_ID = isset($CLMGROUP_MEMBER_CREATOR_ID) ? $CLMGROUP_MEMBER_CREATOR_ID : "";
        $FROM_CLMGROUP_MEMBER_CRE_DATETIME = isset($FROM_CLMGROUP_MEMBER_CRE_DATETIME) ? $FROM_CLMGROUP_MEMBER_CRE_DATETIME : "";
        $TO_CLMGROUP_MEMBER_CRE_DATETIME = isset($TO_CLMGROUP_MEMBER_CRE_DATETIME) ? $TO_CLMGROUP_MEMBER_CRE_DATETIME : "";
        $page = isset($page) ? $page : "";
        $page_len = isset($page_len) ? $page_len : "";
        
        $query = "select * from {$this->table_name } where 1=1 ";

        if ($CLMGROUP_MEMBER_ID != "" ){
            $query .= " and CLMGROUP_MEMBER_ID={$CLMGROUP_MEMBER_ID}";
        }
        if ($CLMGROUP_MEMBER_GROUPID != "" ){
            $query .= " and CLMGROUP_MEMBER_GROUPID={$CLMGROUP_MEMBER_GROUPID}";
        }
        if ($CLMGROUP_MEMBER_USERID != "" ){
            $query .= " and CLMGROUP_MEMBER_USERID={$CLMGROUP_MEMBER_USERID}";
        }
        if ($CLMGROUP_MEMBER_USERTYPE != "" ){
            $query .= " and CLMGROUP_MEMBER_USERTYPE={$CLMGROUP_MEMBER_USERTYPE}";
        }
        if ($CLMGROUP_MEMBER_CREATOR_ID != "" ){
            $query .= " and CLMGROUP_MEMBER_CREATOR_ID={$CLMGROUP_MEMBER_CREATOR_ID}";
        }
        if ($FROM_CLMGROUP_MEMBER_CRE_DATETIME != "" ){
            $query .= " and DATE(CLMGROUP_MEMBER_CRE_DATETIME) >= DATE('{$FROM_CLMGROUP_MEMBER_CRE_DATETIME}')";
        }
        if ($TO_CLMGROUP_MEMBER_CRE_DATETIME != "" ){
            $query .= " and DATE(CLMGROUP_MEMBER_CRE_DATETIME) <= DATE('{$TO_CLMGROUP_MEMBER_CRE_DATETIME}')";
        }

        $page_limit = "";
        if ($page != ""){
            if ($page_len != "" ){
                $this->page_len = $page_len;
            }
            $start = $this->page_len * ((int)$page - 1 );
            $page_limit =" limit {$start},{$this->page_len}";
        }

        $data = [];
        $result = $this->conn->query($query );
		$retrieve_total = $result->num_rows;
        $query .= $page_limit;

        $result = $this->conn->query($query );
        if($result){
			while ($row = $result->fetch_array()){
                $row["tbaccounts_groups"] = $this->table_join_data("tbaccounts_groups", "CLMACCOUNT_GROUP_ID", $row["CLMGROUP_MEMBER_GROUPID"]);
				array_push($data, $row );
			}
		}
		return ["total"=> $retrieve_total, "data"=> $data ];
    }

    public function create($params ){
        extract($params );
        $validate = [];
        $CLMGROUP_MEMBER_GROUPID = isset($CLMGROUP_MEMBER_GROUPID) ? $CLMGROUP_MEMBER_GROUPID : "";
        $CLMGROUP_MEMBER_USERID = isset($CLMGROUP_MEMBER_USERID) ? $CLMGROUP_MEMBER_USERID : "";
        $CLMGROUP_MEMBER_USERTYPE = isset($CLMGROUP_MEMBER_USERTYPE) ? $CLMGROUP_MEMBER_USERTYPE : "";
        $CLMGROUP_MEMBER_CREATOR_ID = isset($CLMGROUP_MEMBER_CREATOR_ID) ? $CLMGROUP_MEMBER_CREATOR_ID : "";
        $FROM_CLMGROUP_MEMBER_CRE_DATETIME = isset($FROM_CLMGROUP_MEMBER_CRE_DATETIME) ? $FROM_CLMGROUP_MEMBER_CRE_DATETIME : "";
        $TO_CLMGROUP_MEMBER_CRE_DATETIME = isset($TO_CLMGROUP_MEMBER_CRE_DATETIME) ? $TO_CLMGROUP_MEMBER_CRE_DATETIME : "";

        if ($CLMGROUP_MEMBER_GROUPID == "" ){
            $validate["CLMGROUP_MEMBER_GROUPID"] = "required";
        }

        if ($CLMGROUP_MEMBER_USERID == "" ){
            $validate["CLMGROUP_MEMBER_USERID"] = "required";
        }
        
        if ($CLMGROUP_MEMBER_CREATOR_ID == "" ){
            $validate["CLMGROUP_MEMBER_CREATOR_ID"] = "required";
        }     

        if ($CLMGROUP_MEMBER_USERTYPE == "" ){
            $validate["CLMGROUP_MEMBER_USERTYPE"] = "required";
        }

        if ($CLMGROUP_MEMBER_CRE_DATETIME == "" ){
            $validate["CLMGROUP_MEMBER_CRE_DATETIME"] = "required";
        }

        if (count($validate ) > 0 ){
            return ["validation"=> $validate, "result"=> false ];
        }

        $query = "insert into {$this->table_name } set CLMGROUP_MEMBER_GROUPID={$CLMGROUP_MEMBER_GROUPID}";
        $query .= ", CLMGROUP_MEMBER_USERID={$CLMGROUP_MEMBER_USERID}";
        $query .= ", CLMGROUP_MEMBER_CREATOR_ID={$CLMGROUP_MEMBER_CREATOR_ID}";
        $query .= ", CLMGROUP_MEMBER_USERTYPE={$CLMGROUP_MEMBER_USERTYPE}";
        $query .= ", CLMGROUP_MEMBER_CRE_DATETIME={$CLMGROUP_MEMBER_CRE_DATETIME}";

        if($this->conn->query($query )){
            return ["validation"=> $validate, "result"=> true ];
        }else{
            return ["validation"=> $validate, "result"=> $this->conn->error ];
        }        
    }

    public function update($params ){
        extract($params );

        $validate = [];
        $CLMGROUP_MEMBER_ID = isset($CLMGROUP_MEMBER_ID) ? $CLMGROUP_MEMBER_ID : "";
        $CLMGROUP_MEMBER_GROUPID = isset($CLMGROUP_MEMBER_GROUPID) ? $CLMGROUP_MEMBER_GROUPID : "";
        $CLMGROUP_MEMBER_USERID = isset($CLMGROUP_MEMBER_USERID) ? $CLMGROUP_MEMBER_USERID : "";
        $CLMGROUP_MEMBER_USERTYPE = isset($CLMGROUP_MEMBER_USERTYPE) ? $CLMGROUP_MEMBER_USERTYPE : "";
        $CLMGROUP_MEMBER_CREATOR_ID = isset($CLMGROUP_MEMBER_CREATOR_ID) ? $CLMGROUP_MEMBER_CREATOR_ID : "";
        $CLMGROUP_MEMBER_CRE_DATETIME = isset($CLMGROUP_MEMBER_CRE_DATETIME) ? $CLMGROUP_MEMBER_CRE_DATETIME : "";


        if ($CLMGROUP_MEMBER_ID == "" ){
            return ["validation"=> $validate, "result"=> "primary key not exist" ];
        }

        $query = "update {$this->table_name } set ";
        $comma = "";

        if ($CLMGROUP_MEMBER_GROUPID == "" ){
            $query .= $comma . "CLMGROUP_MEMBER_GROUPID={$CLMGROUP_MEMBER_GROUPID}";
            $comma = ",";
        }

        if ($CLMGROUP_MEMBER_USERID != "" ){
            $query .= $comma . "CLMGROUP_MEMBER_USERID={$CLMGROUP_MEMBER_USERID}";
            $comma = ",";
        }
        
        if ($CLMGROUP_MEMBER_CREATOR_ID != "" ){
            $query .= $comma . "CLMGROUP_MEMBER_CREATOR_ID={$CLMGROUP_MEMBER_CREATOR_ID}";
            $comma = ",";
        }     

        if ($CLMGROUP_MEMBER_USERTYPE != "" ){
            $query .= $comma . "CLMGROUP_MEMBER_USERTYPE={$CLMGROUP_MEMBER_USERTYPE}";
            $comma = ",";
        }

        if ($CLMGROUP_MEMBER_CRE_DATETIME != "" ){
            $query .= $comma . "CLMGROUP_MEMBER_CRE_DATETIME={$CLMGROUP_MEMBER_CRE_DATETIME}";
        }
        $query .= " WHERE CLMGROUP_MEMBER_ID={$CLMGROUP_MEMBER_ID}";

        if($this->conn->query($query )){
            return ["validation"=> $validate, "result"=> true, "affected_rows"=> $this->conn->affected_rows  ];
        }else{
            return ["validation"=> $validate, "result"=> $this->conn->error ];
        }   
    }

    public function delete($params ){
        extract($params );
        $validate = [];
        $CLMGROUP_MEMBER_ID = isset($CLMGROUP_MEMBER_ID) ? $CLMGROUP_MEMBER_ID : "";

        if ($CLMGROUP_MEMBER_ID == "" ){
            return ["validation"=> $validate, "result"=> "primary key not exist" ];
        }

        $query = "delete from {$this->table_name} WHERE CLMGROUP_MEMBER_ID={$CLMGROUP_MEMBER_ID}";
        if($result = $this->conn->query($query )){
            return ["validation"=> $validate, "result"=> true, "affected_rows"=> $this->conn->affected_rows ];
        }else{
            return ["validation"=> $validate, "result"=> $this->conn->error ];
        }   
    }

    public function add_or_update($params ){
        extract($params );
        $CLMGROUP_MEMBER_ID = isset($CLMGROUP_MEMBER_ID) ? $CLMGROUP_MEMBER_ID : "";
        $tmp = [];
        if ($CLMGROUP_MEMBER_ID == "" ){
            $tmp = $this->create($params );
        }else{
            $result = $this->conn->query($query );
            if ($result->num_num_rows > 0 ){
                $tmp = $this->update($params );
            }else{
                $tmp = $this->create($params );
            }
        }
        return $tmp;
    }

    private function get_record($id ){
        $query = "select * from {$this->table_name } where CLMGROUP_MEMBER_ID={$id}";
        $result = $this->conn->query($query );
        return $result;
    }

    private function table_join_data($table, $field, $value ){
		$query = "select * from {$table } where {$field}=$value";
		$result = $this->conn->query($query );
		$data = [];
		if($result){
			while ($row = $result->fetch_array()){
				array_push($data, $row );
			}
		}
		return $data;
	}

    private function check_date($string) {
        if (strtotime($string)) {
            return true;
        }else {
            return false;
        }
    }
}
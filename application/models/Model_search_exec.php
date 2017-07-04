<?php

class Model_search_exec extends CI_Model{

	public function __construct(){
		parent::__construct();
	}

	public function getUsernames($u){
		$result = "";
		$query = $this->db->query("SELECT username FROM users 
	        WHERE username LIKE '$u%' AND activated='1'
			ORDER BY username ASC");
		$numrows = $query->num_rows();
		if ($numrows < 1){
			return $result;
		} else{
			foreach ($query->result_array() as $row){
				$uname = $row["username"];
				$result .= '<a href="http://www.timecords.com/user/'.$uname.'">'.$uname.'</a><br />';
			}
			return $result;
		}
	}

}
?>
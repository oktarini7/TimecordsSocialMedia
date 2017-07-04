<?php
// Initialize some vars
class Model_check_login_status extends CI_Model{

	private $user_ok = false;
	private $log_id = "";
	private $log_username = "";
	private $log_password = "";

	public function __construct(){
		parent::__construct();
	}

	public function evalLoggedUser($id,$u,$p){
		$requirements = [
			'id' => $id,
			'username' => $u,
			'password' => $p,
			'activated' => '1'
		];
    	$query = $this->db->get_where('users', $requirements, '1');
       	$result= $query->row_array();
		if(!$result){
			return false;
		} else {
			return true;
		}
	}

	public function check_login(){
		if(isset($_SESSION["userid"]) && isset($_SESSION["username"]) && isset($_SESSION["password"])) {
			$this->log_id = $_SESSION['userid'];
			$this->log_username = $_SESSION['username'];
			$this->log_password = $_SESSION['password'];
			// Verify the user
			$this->user_ok = $this->evalLoggedUser($this->log_id,$this->log_username,$this->log_password);
		} else if(isset($_COOKIE["id"]) && isset($_COOKIE["user"]) && isset($_COOKIE["pass"])){
			$_SESSION['userid'] = $_COOKIE['id'];
    		$_SESSION['username'] = $_COOKIE['user'];
    		$_SESSION['password'] = $_COOKIE['pass'];
			$this->log_id = $_SESSION['userid'];
			$this->log_username = $_SESSION['username'];
			$this->log_password = $_SESSION['password'];
			// Verify the user
			$this->user_ok = $this->evalLoggedUser($this->log_id,$this->log_username,$this->log_password);
		}
		if($this->user_ok){
			// Update their lastlogin datetime field
			$data = array(
				'lastlogin' => date('Y-m-d H:i:s')
			);
			$this->db->where('id', $this->log_id);
			$this->db->update('users', $data);
		}
		return $this->user_ok;
	}
	public function get_log_id(){
		return $this->log_id;
	}
	public function get_log_username(){
		return $this->log_username;
	}
	public function get_log_password(){
		return $this->log_password;
	}
}
?>
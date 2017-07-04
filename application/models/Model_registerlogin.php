<?php
// Initialize some vars
class Model_registerlogin extends CI_Model{

	public function __construct(){
		parent::__construct();
	}
	public function getUser($e_login){
		$requirements = [
			'email' => $e_login,
			'activated' => '1'
		];
    	$query = $this->db->get_where('users', $requirements, '1');
       	$result= $query->row_array();
       	return $result;
	}
	public function updateLastLogin($id, $ip){
		$data = array(
				'lastlogin' => date('Y-m-d H:i:s'),
				'ip' => $ip
			);
		$this->db->where('id', $id);
		$this->db->update('users', $data);
	}
	public function checkUsername($username){
		$requirements = [
			'username' => $username
		];
    	$query = $this->db->get_where('users', $requirements, '1');
       	$result= $query->row_array();
       	return $result;
	}
	public function checkEmail($email){
		$requirements = [
			'email' => $email
		];
    	$query = $this->db->get_where('users', $requirements, '1');
       	$result= $query->row_array();
       	return $result;
	}
	public function insertUser($u, $e, $p, $g, $ip){
		$this->load->helper('string');
		$token= random_string('alnum', 16);
		$this->session->set_userdata('token_timecords', $token);
		$data=[
			'username' => $u,
			'email' => $e,
			'password' => $p,
			'gender' => $g,
			'ip' => $ip,
			'signup' => date('Y-m-d H:i:s'),
			'lastlogin' => date('Y-m-d H:i:s'),
			'notescheck' => date('Y-m-d H:i:s'),
			'activated' => '0',
			'token' => $token
		];
		$this->db->insert('users', $data);
		$insert_id = $this->db->insert_id();
		$data2=[
			'id' => $insert_id,
			'username' => $u,
			'background' => 'original'
		];
		$this->db->insert('useroptions', $data2);
		return $insert_id;
	}
	public function verifyActivation($id, $u, $e, $t){
		$requirements = [
			'id' => $id,
			'username' => $u,
			'email' => $e,
			'token' => $t
		];
    	$query = $this->db->get_where('users', $requirements, '1');
       	$result= $query->row_array();
       	return $result;
	}
	public function activateNow($u){
		$data = array(
				'activated' => '1'
			);
		$this->db->where('username', $u);
		return $this->db->update('users', $data);
	}
}
?>
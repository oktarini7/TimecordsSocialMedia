<?php

class Model_indosystem extends CI_Model{

	public function __construct(){
		parent::__construct();
	}

	public function index(){

	}
	public function submitToDatabase($name, $address, $phone, $note){
		$data=[
			'name' => $name,
			'address' => $address,
			'phone' => $phone,
			'note' => $note
		];
		$this->db->insert('guests', $data);
		return true;
	}
	public function getAllNotes(){
		$query = $this->db->get('guests');
		$all= $query->result_array();
		return $all;
	}
}
?>
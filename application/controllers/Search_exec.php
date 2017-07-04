<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_exec extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_user');
		$this->load->model('model_search_exec');
		$this->load->model('model_check_login_status');
		$user_ok2= false;
		$user_ok2= $this->model_check_login_status->check_login();
		if (!$user_ok2){
			redirect('message/not_loggedin');
			exit();
		}
	}

	public function search()
	{
		$output = "";
		$u= $this->input->post('u');
		if ($u == ""){
			echo $output;
			exit;	
		} else {
			$output= $this->model_search_exec->getUsernames($u);
			echo $output;
			exit();
		}
	}

}
?>